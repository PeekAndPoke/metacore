<?php
/**
 * Created by gerk on 01.10.16 17:24
 */

namespace PeekAndPoke\Component\MetaCore;

use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRef;
use PeekAndPoke\Component\MetaCore\DomainModel\Visibility;
use PeekAndPoke\Component\MetaCore\Exception\MetaCoreRuntimeException;
use PeekAndPoke\Component\Psi\Psi;
use PeekAndPoke\Component\Psi\Psi\IsInstanceOf;
use PeekAndPoke\Component\Psi\Psi\IsNotInstanceOf;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types;

/**
 * DefaultPropertyMapper
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DefaultPropertyMapper implements PropertyMapper
{
    /** @var DocBlockFactory */
    private $docBlockFactory;

    public function __construct()
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    /**
     * @param Builder             $builder
     * @param \ReflectionProperty $property
     *
     * @return Property
     */
    public function mapProperty(Builder $builder, \ReflectionProperty $property)
    {
        $docBlock = $this->readDocBlock($property);
        $varTag   = $this->getVarTag($docBlock);

        return new Property(
            $property->getName(),
            $this->mapType($builder, $varTag->getType()),
            Visibility::fromReflection($property),
            $this->isNullable($varTag->getType()),
            new DomainModel\Docs\Doc(
                (string) $docBlock->getSummary(),
                (string) $docBlock->getDescription()
            )
        );
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return DocBlock
     */
    protected function readDocBlock(\ReflectionProperty $property)
    {
        // get the Class OR Trait that really defines the property -> this is needed for the DocBlock reader
        $declaringClass  = Builder::getRealDeclaringClass($property);
        $docBlockContext = (new Types\ContextFactory())->createFromReflector($declaringClass);

        return $this->docBlockFactory->create($property->getDocComment(), $docBlockContext);
    }

    /**
     * @param DocBlock $docBlock
     *
     * @return DocBlock\Tags\Var_
     */
    protected function getVarTag(DocBlock $docBlock)
    {
        $varTags = $docBlock->getTagsByName('var');

        if (count($varTags) === 0) {
            throw MetaCoreRuntimeException::noVarTagFound();
        }

        return $varTags[0];
    }


    /**
     * @param \phpDocumentor\Reflection\Type $type
     *
     * @return bool
     */
    protected function isNullable(\phpDocumentor\Reflection\Type $type)
    {
        if ($type instanceof Types\Null_) {
            return true;
        }

        if ($type instanceof Types\Compound) {

            return Psi::it($this->getCompoundChildren($type))
                       ->filter(new IsInstanceOf(Types\Null_::class))
                       ->count() > 0;
        }

        return false;
    }

    /**
     * @param Types\Compound $compound
     *
     * @return \phpDocumentor\Reflection\Type[]
     */
    protected function getCompoundChildren(Types\Compound $compound)
    {
        $reflect = new \ReflectionClass(Types\Compound::class);
        $prop    = $reflect->getProperty('types');
        $prop->setAccessible(true);

        return $prop->getValue($compound);
    }

    /**
     * @param Builder                        $builder
     * @param \phpDocumentor\Reflection\Type $type
     *
     * @return TypeRef
     */
    private function mapType(Builder $builder, \phpDocumentor\Reflection\Type $type)
    {
        ////  MULTIPLE TYPE-HINTS  (COMPOUND)  //////////////////////////////////////////////////////////////////////

        // If there are multiple type hints we try to find the first one that is not NULL_
        if ($type instanceof Types\Compound) {
            $type = Psi::it($this->getCompoundChildren($type))
                ->filter(new IsNotInstanceOf(Types\Null_::class))
                ->filter(new IsInstanceOf(\phpDocumentor\Reflection\Type::class))
                ->getFirst();

            if ($type === null) {
                throw MetaCoreRuntimeException::compoundTypeWithNullsOnly();
            }

            return $this->mapType($builder, $type);
        }

        ////  BASIC TYPES  //////////////////////////////////////////////////////////////////////////////////////////

        if ($type instanceof Types\Boolean) {
            return Type::boolean()->ref();
        }
        if ($type instanceof Types\Float_) {
            return Type::float()->ref();
        }
        if ($type instanceof Types\Mixed) {
            return Type::any()->ref();
        }
        if ($type instanceof Types\Integer) {
            return Type::int()->ref();
        }
        if ($type instanceof Types\String_) {
            return Type::string()->ref();
        }
        if ($type instanceof Types\Scalar) {
            return Type::string()->ref();
        }

        ////  ARRAYS   ////////////////////////////////////////////////////////////////////////////////////////////////

        if ($type instanceof Types\Array_) {
            return Type::map(
                Type::string()->ref(),
                $this->mapType($builder, $type->getValueType())
            )->ref();
        }

        ////  OBJECTS  ////////////////////////////////////////////////////////////////////////////////////////////////

        if ($type instanceof Types\Object_) {

            // In this case we saw something like
            // @var object
            if ($type->getFqsen() === null) {
                return Type::any()->ref();
            }

            try {
                $class = new \ReflectionClass((string) $type);
            } catch (\ReflectionException $e) {
                throw MetaCoreRuntimeException::unknownType($e);
            }

            // do the RECURSION for another real type
            return $builder->buildForClass($class)->ref();
        }

        ////  SPECIALS  ////////////////////////////////////////////////////////////////////////////////////////////////

        if ($type instanceof Types\Null_) {
            return Type::any()->ref();
        }

        // we default to this one:

        return Type::any()->ref();
    }
}
