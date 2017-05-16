<?php
/**
 * Created by gerk on 06.10.16 06:42
 */

namespace PeekAndPoke\Component\MetaCore\Tests\Unit;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\IPropertyFilter;
use PeekAndPoke\Component\MetaCore\IPropertyMapper;
use PeekAndPoke\Component\MetaCore\Tests\Stubs\xxxMetaCoreUnitTestClassWithTypeAliasxxx;
use PeekAndPoke\Component\MetaCore\Tests\Stubs\xxxMetaCoreUnitTestEnumxxx;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class BuilderTest extends TestCase
{

    public function testConstruction()
    {
        AnnotationRegistry::registerLoader(function ($class) {
            return class_exists($class) || interface_exists($class) || trait_exists($class);
        });

        $reader = new AnnotationReader();
        /** @var IPropertyFilter $filter */
        $filter = $this->getMockBuilder(IPropertyFilter::class)->getMockForAbstractClass();
        /** @var IPropertyMapper $mapper */
        $mapper = $this->getMockBuilder(IPropertyMapper::class)->getMockForAbstractClass();

        $builder = new Builder($reader, $filter, $mapper);

        static::assertSame($filter, $builder->getPropertyFilter(), 'The PropertyFilter must be setup correctly');

        static::assertSame($mapper, $builder->getPropertyMapper(), 'The PropertyMapper must be setup correctly');

        static::assertInstanceOf(MetaCore\TypeRegistry::class, $builder->getTypeRegistry(), 'The TypeRegistry must be initialized');

        static::assertCount(8, $builder->getBuiltInTypes(), 'The number of built in types must be correct');
    }

    /**
     * This test ensures that building a type from identical references will return the same type object.
     *
     * So with the first call the type object is constructed.
     * On every consecutive call the same type objects must be returned
     *
     * @param Type $input
     *
     * @dataProvider provideTestBuildForRefOmnipotency
     */
    public function testBuildForRefOmnipotency(Type $input)
    {
        $builder = Builder::createDefault();
        $ref     = $input->ref();

        $built    = $builder->buildForRef($ref);
        $builtTwo = $builder->buildForRef($ref);

        static::assertSame($input->getId(), $built->getId(), 'The type must be build correctly');

        static::assertSame($built, $builtTwo, "Building the type twice must return the same Type object for '{$builder->buildFullName($ref)}'");
    }

    /**
     * @return array
     */
    public static function provideTestBuildForRefOmnipotency()
    {
        return [
            [
                Type::any(),
            ],
            [
                Type::boolean(),
            ],
            [
                Type::double(),
            ],
            [
                Type::float(),
            ],
            [
                Type::int(),
            ],
            [
                Type::string(),
            ],
            [
                Type::dateTime(),
            ],
            [
                Type::localDateTime(),
            ],
            [
                Type::enum(xxxMetaCoreUnitTestEnumxxx::void()),
            ],
            [
                Type::object(Type\AnyType::class),
            ],
            [
                Type::object(Type\IntType::class),
            ],
            [
                Type::list_(Type::string()->ref()),
            ],
            [
                Type::list_(Type::any()->ref()),
            ],
            [
                Type::map(Type::string()->ref(), Type::int()->ref()),
            ],
            [
                Type::map(Type::string()->ref(), Type::any()->ref()),
            ],
        ];
    }

    /**
     * Test that the full names are built correctly
     *
     * @param Type   $type
     * @param string $expectedFullName
     *
     * @dataProvider provideTestBuildFullName
     */
    public function testBuildFullName(Type $type, string $expectedFullName)
    {
        $builder = Builder::createDefault();

        self::assertSame(
            $expectedFullName,
            $builder->buildFullName($type->ref()),
            'The full name must be built correctly'
        );
    }

    public function provideTestBuildFullName()
    {
        return [
            [
                Type::any(),
                Type\AnyType::TYPE,
            ],
            [
                Type::boolean(),
                Type\BooleanType::TYPE,
            ],
            [
                Type::double(),
                Type\DoubleType::TYPE,
            ],
            [
                Type::float(),
                Type\FloatType::TYPE,
            ],
            [
                Type::int(),
                Type\IntType::TYPE,
            ],
            [
                Type::string(),
                Type\StringType::TYPE,
            ],
            [
                Type::dateTime(),
                Type\DateTimeType::TYPE,
            ],
            [
                Type::localDateTime(),
                Type\LocalDateTimeType::TYPE,
            ],
            [
                // test enum without alias
                Type::enum(xxxMetaCoreUnitTestEnumxxx::void()),
                xxxMetaCoreUnitTestEnumxxx::class,
            ],
            [
                // test enum with an alias
                Type::enum(xxxMetaCoreUnitTestEnumxxx::void(), 'enum.alias'),
                'enum.alias',
            ],
            [
                // test a type without an alias
                Type::object(Type\AnyType::class),
                Type\AnyType::class,
            ],
            [
                // test a type with an alias
                Type::object(Type\AnyType::class, 'object.alias'),
                'object.alias',
            ],
            [
                Type::list_(Type::string()->ref()),
                'List<String>',
            ],
            [
                Type::list_(Type::any()->ref()),
                'List<*>',
            ],
            [
                Type::map(Type::string()->ref(), Type::int()->ref()),
                'Map<String,Int>',
            ],
            [
                Type::map(Type::int()->ref(), Type::any()->ref()),
                'Map<Int,*>',
            ],
        ];
    }

    public function testBuildForClassThatHasAnAliasAnnotation()
    {
        $builder = Builder::createDefault();

        $type = $builder->buildForClass(new \ReflectionClass(xxxMetaCoreUnitTestClassWithTypeAliasxxx::class));

        self::assertSame(
            xxxMetaCoreUnitTestClassWithTypeAliasxxx::ALIAS,
            $type->getId(),
            'The alias must be read correctly from class'
        );
    }
}
