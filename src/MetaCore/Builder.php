<?php
/**
 * Created by gerk on 29.09.16 10:21
 */

namespace PeekAndPoke\Component\MetaCore;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRef;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRegistry;
use PeekAndPoke\Component\MetaCore\Exception\MetaCoreRuntimeException;
use PeekAndPoke\Component\Psi\Psi;
use PeekAndPoke\Component\Slumber\Annotation\Slumber\Alias;
use PeekAndPoke\Types\Enumerated;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types;


/**
 * Builder
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class Builder
{
    /** @var Reader */
    private $reader;
    /** @var IPropertyFilter */
    private $propertyFilter;
    /** @var IPropertyMapper */
    private $propertyMapper;

    /** @var TypeRegistry */
    private $typeRegistry;
    /** @var DocBlockFactory */
    private $docBlockFactory;
    /** @var Type[] Built in type, key being the type name, value a blueprint instance */
    private $builtInTypes;

    /**
     * @return Builder
     */
    public static function createDefault()
    {
        static $autoloaderRegistered = false;

        if (!$autoloaderRegistered) {
            $autoloaderRegistered = true;

            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            AnnotationRegistry::registerLoader(
                function ($class) {
                    return class_exists($class) || interface_exists($class) || trait_exists($class);
                }
            );
        }

        return new static(
            new CachedReader(new AnnotationReader(), new ArrayCache()),
            new DefaultPropertyFilter(),
            new DefaultPropertyMapper()
        );
    }

    /**
     * C'tor
     *
     * @param Reader          $reader
     * @param IPropertyFilter $propertyFilter
     * @param IPropertyMapper $propertyMapper
     */
    public function __construct(Reader $reader, IPropertyFilter $propertyFilter, IPropertyMapper $propertyMapper)
    {
        $this->reader         = $reader;
        $this->propertyFilter = $propertyFilter;
        $this->propertyMapper = $propertyMapper;

        $this->typeRegistry    = new TypeRegistry();
        $this->docBlockFactory = DocBlockFactory::createInstance();

        $this->builtInTypes = [
            DomainModel\Type\AnyType::TYPE => new DomainModel\Type\AnyType(),
            DomainModel\Type\BooleanType::TYPE => new DomainModel\Type\BooleanType(),
            DomainModel\Type\DateTimeType::TYPE => new DomainModel\Type\DateTimeType(),
            DomainModel\Type\DoubleType::TYPE => new DomainModel\Type\DoubleType(),
            DomainModel\Type\FloatType::TYPE => new DomainModel\Type\FloatType(),
            DomainModel\Type\IntType::TYPE => new DomainModel\Type\IntType(),
            DomainModel\Type\LocalDateTimeType::TYPE => new DomainModel\Type\LocalDateTimeType(),
            DomainModel\Type\StringType::TYPE => new DomainModel\Type\StringType(),
        ];
    }

    /**
     * @return IPropertyFilter
     */
    public function getPropertyFilter()
    {
        return $this->propertyFilter;
    }

    /**
     * @return IPropertyMapper
     */
    public function getPropertyMapper()
    {
        return $this->propertyMapper;
    }

    /**
     * @return TypeRegistry
     */
    public function getTypeRegistry()
    {
        return $this->typeRegistry;
    }

    /**
     * @return Type[]
     */
    public function getBuiltInTypes(): array
    {
        return $this->builtInTypes;
    }

    /**
     * Build the full name for a type.
     *
     * For simple types this will return "String", "Int", etc.
     *
     * For generic type this will return e.g. Map<String,List<Int>>
     *
     * @param TypeRef $ref
     *
     * @return string
     */
    public function buildFullName(TypeRef $ref): string
    {
        if (count($ref->getParams()) === 0) {
            return $ref->getId();
        }

        $params = [];

        foreach ($ref->getParams() as $param) {
            $params[] = $this->buildFullName(
                $param
            );
        }

        return $ref->getId() . '<' . implode(',', $params) . '>';
    }

    /**
     * Build a type from a given TypeRef.
     *
     * This method is omnipotent, meaning that when ever we request a type that is already
     * known, we will return the same instance of the Type.
     *
     * A type is considered to be known when the fullName
     * a) matches a built in type like "String", "DateTime", "Int"
     * b) is already present in the type registry
     *
     * @param TypeRef $ref
     *
     * @return Type
     */
    public function buildForRef(TypeRef $ref)
    {
        $fullName = $this->buildFullName($ref);

        if (isset($this->builtInTypes[$fullName])) {
            return $this->builtInTypes[$fullName];
        }

        if ($this->typeRegistry->hasById($fullName)) {
            return $this->typeRegistry->getById($fullName);
        }

        if ($ref->getId() === Type\MapType::type()) {
            $type = Type::map(
                $ref->getParams()[0] ?? Type::any()->ref(),
                $ref->getParams()[1] ?? Type::any()->ref()
            );

            $this->typeRegistry->add($fullName, $type);

            return $type;
        }

        if ($ref->getId() === Type\ListType::type()) {
            $type = Type::list_($ref->getParams()[0] ?? Type::any()->ref());

            $this->typeRegistry->add($fullName, $type);

            return $type;
        }

        return $this->buildForClass(new \ReflectionClass($ref->getId()));
    }

    /**
     * Build the type from a given reflection class.
     *
     * @param \ReflectionClass $class
     *
     * @return Type
     */
    public function buildForClass(\ReflectionClass $class)
    {
        $id = $class->getName();

        // is there an alias defined on the class using the Slumber\Alias annotation
        if (class_exists(Alias::class)) {
            /** @var Alias|null $aliasAnnotation */
            $aliasAnnotation = $this->reader->getClassAnnotation($class, Alias::class);
            // do we have an alias ?
            $id = $aliasAnnotation ? $aliasAnnotation->value : $id;
        }

        if ($this->typeRegistry->hasById($id)) {
            return $this->typeRegistry->getById($id);
        }

        if (is_a($class->getName(), Enumerated::class, true)) {

            /** @var Enumerated $enum */
            $enum = $class->getName();
            $type = Type::enum($enum::void());

            $this->typeRegistry->add($this->buildFullName($type->ref()), $type);

        } else {
            // IMPORTANT: we initiate and register an "empty" type in order to break cyclic references.
            $type = new ObjectType($id);
            $this->typeRegistry->add($this->buildFullName($type->ref()), $type);

            // populate the type
            $this->populateObjectType($type, $class);
        }

        return $type;
    }

    /**
     * @param ObjectType $type
     *
     * @return Property[]
     */
    public function getAllProperties(ObjectType $type)
    {
        $result = $type->getProperties();

        foreach ($type->getExtends() as $extend) {

            $extendType = $this->buildForRef($extend);

            if ($extendType instanceof ObjectType) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, $this->getAllProperties($extendType));
            }
        }

        return $result;
    }

    /**
     * @param ObjectType       $type
     * @param \ReflectionClass $class
     */
    private function populateObjectType(ObjectType $type, \ReflectionClass $class)
    {
        /** @var Property[] $properties */
        $properties = Psi::it($class->getProperties())
            // is it a non-static property
            ->filter(
                function (\ReflectionProperty $p) { return $p->isStatic() === false; }
            )
            // is the property declared by the Class or one of the Traits it uses ?
            ->filter(
                function (\ReflectionProperty $p) use ($class) {
                    return $this->getDeclaringClassInInheritanceChain($class, $p) === $class;
                }
            )
            // filter properties
            ->filter(
                function (\ReflectionProperty $p) { return $this->propertyFilter->filterProperty($p); }
            )
            // map properties
            ->map(
                function (\ReflectionProperty $p) { return $this->buildProperty($p); }
            )
            // collect
            ->toArray();

        // add all the found properties
        foreach ($properties as $property) {
            $type->addProperty($property);
        }

        // do we inherit ?
        if ($class->getParentClass()) {
            $type->addExtends(
                $this->buildForClass($class->getParentClass())->ref()
            );
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return Property
     */
    private function buildProperty(\ReflectionProperty $property)
    {
        // get the Class OR Trait that really defines the property -> this is needed for the DocBlock reader
        $declaringClass  = $this->getRealDeclaringClass($property->getDeclaringClass(), $property);
        $docBlockContext = (new Types\ContextFactory())->createFromReflector($declaringClass);

        try {
            $docBlock = $this->docBlockFactory->create($property->getDocComment(), $docBlockContext);

            return $this->propertyMapper->mapProperty($this, $property, $docBlock);

        } catch (\Exception $e) {
            throw new MetaCoreRuntimeException(
                'Error in context of ' . $declaringClass->getName() . '::' . $property->getName(), 0, $e
            );
        }
    }

    /**
     * Get the exact class or trait that defines a property.
     *
     * TODO: should we extract extended reflection functionality into the Mirror ?
     *
     * @param \ReflectionClass    $class
     * @param \ReflectionProperty $prop
     *
     * @return \ReflectionClass
     */
    private function getRealDeclaringClass(\ReflectionClass $class, \ReflectionProperty $prop)
    {
        $declaringTrait = Psi::it($class->getTraits())
            ->filter(function (\ReflectionClass $r) use ($prop) { return $r->hasProperty($prop->getName()); })
            ->getFirst(null);

        // We found it on the traits. No we need to recurse on traits to find exactly the one that was defining it
        if ($declaringTrait !== null) {
            return $this->getRealDeclaringClass($declaringTrait, $prop);
        }

        return $this->getDeclaringClassInInheritanceChain($class, $prop);
    }

    /**
     * Get the class declaring the property after traits are resolved.
     *
     * TODO: should we extract extended reflection functionality into the Mirror ?
     *
     * @param \ReflectionClass    $class
     * @param \ReflectionProperty $prop
     *
     * @return \ReflectionClass
     */
    private function getDeclaringClassInInheritanceChain(\ReflectionClass $class, \ReflectionProperty $prop)
    {
        // climb up the inheritance tree
        while ($class->getParentClass() && $class->getParentClass()->hasProperty($prop->getName())) {
            $class = $class->getParentClass();
        }

        // this is what is left
        return $class;
    }
}
