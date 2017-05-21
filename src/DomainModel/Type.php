<?php
/**
 * Created by gerk on 22.09.16 16:44
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType;
use PeekAndPoke\Component\Slumber\Annotation\Slumber;
use PeekAndPoke\Types\Enumerated;


/**
 * Base class for all types
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 *
 * @Slumber\Polymorphic(
 *      {
 *          Type\AnyType::TYPE     : Type\AnyType::class,
 *          Type\BooleanType::TYPE : Type\BooleanType::class,
 *          Type\DoubleType::TYPE  : Type\DoubleType::class,
 *          Type\FloatType::TYPE   : Type\FloatType::class,
 *          Type\IntType::TYPE     : Type\IntType::class,
 *          Type\StringType::TYPE  : Type\StringType::class,
 *
 *          Type\DateTimeType::TYPE      : Type\DateTimeType::class,
 *          Type\LocalDateTimeType::TYPE : Type\LocalDateTimeType::class,
 *
 *          Type\MapType::TYPE  : Type\MapType::class,
 *          Type\ListType::TYPE : Type\ListType::class,
 *
 *          Type\EnumType::TYPE   : Type\EnumType::class,
 *          Type\ObjectType::TYPE : Type\ObjectType::class,
 *      },
 *      tellBy = "_"
 * )
 */
abstract class Type
{
    /**
     * The internal discriminator
     *
     * @var string
     *
     * @Slumber\AsString()
     */
    protected $_;

    ////  WILDCARD  ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return Type\AnyType
     */
    public static function any()
    {
        return new Type\AnyType();
    }

    ////  SCALARS  ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return Type\BooleanType
     */
    public static function boolean()
    {
        return new Type\BooleanType();
    }

    /**
     * @return Type\DoubleType
     */
    public static function double()
    {
        return new Type\DoubleType();
    }

    /**
     * @return Type\FloatType
     */
    public static function float()
    {
        return new Type\FloatType();
    }

    /**
     * @return Type\IntType
     */
    public static function int()
    {
        return new Type\IntType();
    }

    /**
     * @return Type\StringType
     */
    public static function string()
    {
        return new Type\StringType();
    }

    ////  DATE-TIME  ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return Type\DateTimeType
     */
    public static function dateTime()
    {
        return new Type\DateTimeType();
    }

    /**
     * @return Type\LocalDateTimeType
     */
    public static function localDateTime()
    {
        return new Type\LocalDateTimeType();
    }

    ////  ENUMS  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param Enumerated  $enum
     * @param string|null $alias Optional alias name for the object
     *
     * @return Type\EnumType
     */
    public static function enum(Enumerated $enum, $alias = null)
    {
        return new Type\EnumType($enum, $alias);
    }

    ////  OBJECTS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param mixed       $class An object instance or a class name or a random name
     * @param string|null $alias Optional alias name for the object
     *
     * @return ObjectType
     */
    public static function object($class, $alias = null)
    {
        return new Type\ObjectType($class, $alias);
    }

    ////  CONTAINERS  ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param TypeRef $valueType
     *
     * @return Type\ListType
     */
    public static function list_(TypeRef $valueType)
    {
        return new Type\ListType($valueType);
    }

    /**
     * @param TypeRef $keyTypeRef
     * @param TypeRef $valueTypeRef
     *
     * @return Type\MapType
     */
    public static function map(TypeRef $keyTypeRef, TypeRef $valueTypeRef)
    {
        return new Type\MapType($keyTypeRef, $valueTypeRef);
    }

    /**
     * Type constructor.
     */
    public function __construct()
    {
        $this->_ = static::type();
    }

    /**
     * Get the polymorphic type of the object.
     *
     * This is needed for slumber/awake - serialisation
     *
     * @return string
     */
    public static function type()
    {
        throw new \LogicException(static::class . '::type() must be implemented!');
    }

    /**
     * Get the id of the type.
     *
     * By default the id is the same as the type "_" used for polymorphic slumber/awake serialization.
     *
     * This method is overridden e.g. for generic type like "List", "Map" or enums and objects.
     *
     * @return string
     */
    public function getId()
    {
        return $this->_;
    }

    /**
     * Get a reference to this type.
     *
     * @return TypeRef
     */
    public function ref()
    {
        return new TypeRef($this->getId());
    }
}
