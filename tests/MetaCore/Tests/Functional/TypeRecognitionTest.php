<?php
/**
 * Created by gerk on 04.10.16 14:43
 */

namespace PeekAndPoke\Component\MetaCore\Tests\Functional;

use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeRecognitionTest extends TestCase
{
    /** @var Builder */
    private static $builder;
    /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType */
    private static $type;

    /**
     * We are not doing the setup in the setUpBeforeClass in order to get the code coverage reported correctly
     */
    public function setUp()
    {
        if (static::$builder === null) {

            static::$builder = Builder::createDefault();

            /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType $type */
            static::$type = static::$builder->buildForClass(new \ReflectionClass(__MetaCoreTypeRecognitionTestSubject::class));
        }
    }

    public function testGeneralBuilderResult()
    {
        static::assertInstanceOf(MetaCore\Type\ObjectType::class, static::$type, 'Must be an instance of ObjectType');
    }

    public function testIntType()
    {
        $def = static::$type->getPropertyByName('anInt');

        static::assertNotNull($def, 'The property must recognized');
        static::assertEquals(MetaCore\Type\IntType::type(), $def->getTypeRef()->getId(), 'The property must be recognized as an integer type');
        static::assertEquals(false, $def->isNullable(), 'The property MUST NOT be recognized as nullable');
    }

    public function testNullableIntType()
    {
        $def = static::$type->getPropertyByName('aNullableInt');

        static::assertNotNull($def, 'The property must recognized');
        static::assertEquals(MetaCore\Type\IntType::type(), $def->getTypeRef()->getId(), 'The property must be recognized as an integer type');
        static::assertEquals(true, $def->isNullable(), 'The property MUST be recognized as nullable');
    }

    /**
     * @param string  $name
     * @param string  $typeId
     * @param string  $polymorphicType
     * @param boolean $nullable
     *
     * @dataProvider provideTestPropertyRecognition
     */
    public function testPropertyRecognition($name, $typeId, $polymorphicType, $nullable)
    {
        $propertyDefinition = static::$type->getPropertyByName($name);

        static::assertNotNull(
            $propertyDefinition,
            'The property must recognized'
        );

        static::assertEquals(
            $typeId,
            $propertyDefinition->getTypeRef()->getId(),
            'The property must be recognized as type ' . $typeId
        );

        $propertyType = static::$builder->buildForRef($propertyDefinition->getTypeRef());

        static::assertEquals(
            $polymorphicType,
            $propertyType::type(),
            'The property must be recognized as type ' . $typeId
        );

        static::assertEquals(
            $nullable,
            $propertyDefinition->isNullable(),
            'The property ' . ($nullable ? 'MUST' : 'MUST NOT') . ' be recognized as nullable'
        );
    }

    /**
     * @return array
     */
    public static function provideTestPropertyRecognition()
    {
        return [
            [
                'anInt',
                MetaCore\Type\IntType::type(),
                MetaCore\Type\IntType::type(),
                false,
            ],
            [
                'aNullableInt',
                MetaCore\Type\IntType::type(),
                MetaCore\Type\IntType::type(),
                true,
            ],
            [
                'aFloat',
                MetaCore\Type\FloatType::type(),
                MetaCore\Type\FloatType::type(),
                false,
            ],
            [
                'aNullableFloat',
                MetaCore\Type\FloatType::type(),
                MetaCore\Type\FloatType::type(),
                true,
            ],
            [
                'aDouble',
                MetaCore\Type\FloatType::type(),
                MetaCore\Type\FloatType::type(),
                false,
            ],
            [
                'aBool',
                MetaCore\Type\BooleanType::type(),
                MetaCore\Type\BooleanType::type(),
                false,
            ],
            [
                'aBoolean',
                MetaCore\Type\BooleanType::type(),
                MetaCore\Type\BooleanType::type(),
                false,
            ],
            [
                'aString',
                MetaCore\Type\StringType::type(),
                MetaCore\Type\StringType::type(),
                false,
            ],
            [
                'aMixed',
                MetaCore\Type\AnyType::type(),
                MetaCore\Type\AnyType::type(),
                false,
            ],
            [
                'aReferencedType',
                __MetaCoreTypeRecognitionTestUsedBySubject::class,
                MetaCore\Type\ObjectType::type(),
                false,
            ],

            ////  SPECIALS  ////////////////////////////////////////////////////////////////////////////////////
            [
                'aScalar',
                MetaCore\Type\StringType::type(),
                MetaCore\Type\StringType::type(),
                false,
            ],
            [
                'anObject',
                MetaCore\Type\AnyType::type(),
                MetaCore\Type\AnyType::type(),
                false,
            ],
            [
                'aNull',
                MetaCore\Type\AnyType::type(),
                MetaCore\Type\AnyType::type(),
                true,
            ],

            ////  DEFAULTING TO Type::Any  /////////////////////////////////////////////////////////////////////
            [
                'aResource',
                MetaCore\Type\AnyType::type(),
                MetaCore\Type\AnyType::type(),
                false,
            ],
        ];
    }

    public function testAnArray()
    {
        $def = static::$type->getPropertyByName('anArray');

        /** @var MetaCore\Type\MapType $type */
        $type = static::$builder->buildForRef($def->getTypeRef());

        static::assertEquals(
            MetaCore\Type\MapType::type(),
            $type->getId(),
            'The property must be recognized a Map type array'
        );

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $type->getKeyTypeRef()->getId(),
            'The key type must be StringType'
        );

        static::assertEquals(
            MetaCore\Type\AnyType::type(),
            $type->getValueTypeRef()->getId(),
            'The value type must be AnyType'
        );
    }

    public function testAnArrayOfInts()
    {
        $def = static::$type->getPropertyByName('anArrayOfInts');

        /** @var MetaCore\Type\MapType $type */
        $type = static::$builder->buildForRef($def->getTypeRef());

        static::assertEquals(
            MetaCore\Type\MapType::type(),
            $type->getId(),
            'The property must be recognized a Map type array'
        );

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $type->getKeyTypeRef()->getId(),
            'The key type must be StringType'
        );

        static::assertEquals(
            MetaCore\Type\IntType::type(),
            $type->getValueTypeRef()->getId(),
            'The value type must be AnyType'
        );
    }

    public function testAnArrayOfStrings()
    {
        $def = static::$type->getPropertyByName('anArrayOfStrings');

        /** @var MetaCore\Type\MapType $type */
        $type = static::$builder->buildForRef($def->getTypeRef());

        static::assertEquals(
            MetaCore\Type\MapType::type(),
            $type->getId(),
            'The property must be recognized a Map type array'
        );

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $type->getKeyTypeRef()->getId(),
            'The key type must be StringType'
        );

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $type->getValueTypeRef()->getId(),
            'The value type must be StringType'
        );
    }

    public function testAnArrayOfArraysOfFloats()
    {
        $def = static::$type->getPropertyByName('anArrayOfArraysOfFloats');

        /** @var MetaCore\Type\MapType $type */
        $type = static::$builder->buildForRef($def->getTypeRef());

        static::assertEquals(
            MetaCore\Type\MapType::type(),
            $type->getId(),
            'The property must be recognized a Map type array'
        );

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $type->getKeyTypeRef()->getId(),
            'The key type must be StringType'
        );

        static::assertEquals(
            MetaCore\Type\MapType::type(),
            $type->getValueTypeRef()->getId(),
            'The value type must be MapType'
        );

        /** @var MetaCore\Type\MapType $nestedMapType */
        $nestedMapType = static::$builder->buildForRef($type->getValueTypeRef());

        static::assertEquals(
            MetaCore\Type\StringType::type(),
            $nestedMapType->getKeyTypeRef()->getId(),
            'The key type of the nested type must be StringType'
        );

        static::assertEquals(
            MetaCore\Type\FloatType::type(),
            $nestedMapType->getValueTypeRef()->getId(),
            'The value type of the nested type must be FloatType'
        );
    }
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreTypeRecognitionTestUsedBySubject
{

}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreTypeRecognitionTestSubject
{
    /**
     * @var int
     */
    public $anInt = 0;
    /**
     * @var int|null
     */
    public $aNullableInt = 0;
    /**
     * @var float
     */
    public $aFloat = 0.0;
    /**
     * @var null|float
     */
    public $aNullableFloat = 0.0;
    /**
     * @var double
     */
    public $aDouble = 0.0;
    /**
     * @var bool
     */
    public $aBool = false;
    /**
     * @var boolean
     */
    public $aBoolean = false;
    /**
     * @var string
     */
    public $aString = '';
    /**
     * @var mixed
     */
    public $aMixed = '';
    /**
     * @var __MetaCoreTypeRecognitionTestUsedBySubject
     */
    public $aReferencedType;

    ////  SPECIALS  //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /** @noinspection PhpUndefinedClassInspection */
    /**
     * @var scalar
     */
    public $aScalar = '';
    /** @noinspection GenericObjectTypeUsageInspection */
    /**
     * @var object
     */
    public $anObject;
    /**
     * @var null
     */
    public $aNull;

    ////  DEFAULTING TO Type::Any  ////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @var resource
     */
    public $aResource;

    ////  ARRAYS  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @var array
     */
    public $anArray = [];
    /**
     * @var int[]
     */
    public $anArrayOfInts = [];
    /**
     * @var string[]
     */
    public $anArrayOfStrings = [];
    /**
     * @var float[][]
     */
    public $anArrayOfArraysOfFloats = [];
}
