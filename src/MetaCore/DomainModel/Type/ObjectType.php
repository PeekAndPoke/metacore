<?php
/**
 * Created by gerk on 22.09.16 16:46
 */
namespace PeekAndPoke\Component\MetaCore\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Docs;
use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRef;
use PeekAndPoke\Component\Slumber\Annotation\Slumber;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class ObjectType extends Type
{
    public const TYPE = 'Object';

    /**
     * The id of the type
     *
     * @var string
     *
     * @Slumber\AsString()
     */
    protected $id;

    /**
     * @var Docs\Doc
     *
     * @Slumber\AsObject(Docs\Doc::class)
     */
    protected $doc;

    /**
     * @var Property[]
     *
     * @Slumber\AsList(
     *     @Slumber\AsObject(Property::class)
     * )
     */
    protected $properties = [];

    /**
     * Vertical inheritance - object types this type inherits from
     *
     * @var TypeRef[]
     *
     * @Slumber\AsList(
     *     @Slumber\AsObject(TypeRef::class)
     * )
     */
    protected $extends = [];

    /**
     * @param mixed       $class An object instance or a class name or a random name
     * @param string|null $alias
     *
     * @internal param string $id
     */
    public function __construct($class, string $alias = null)
    {
        parent::__construct();

        /** @noinspection NestedTernaryOperatorInspection */
        $this->id = $alias ?: (is_object($class) ? get_class($class) : (string) $class);
    }

    public static function type() : string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return Docs\Doc
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @param Docs\Doc $doc
     *
     * @return $this
     */
    public function setDoc($doc)
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * Get properties defined by this object
     *
     * @return Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $name
     *
     * @return Property|null
     */
    public function getPropertyByName($name)
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        return null;
    }

    /**
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @return TypeRef[]
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * @param TypeRef $extends
     *
     * @return $this
     */
    public function addExtends(TypeRef $extends)
    {
        $this->extends[] = $extends;

        return $this;
    }
}
