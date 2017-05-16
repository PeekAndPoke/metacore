<?php
/**
 * Created by gerk on 04.10.16 17:14
 */

namespace PeekAndPoke\Component\MetaCore\Tests\Functional;

use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PeekAndPoke\Component\MetaCore\Tests\Stubs\xxxMetaCoreUnitTestEnumxxx;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class EnumerationTest extends TestCase
{
    /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\EnumType */
    private $type;

    public function setUp()
    {
        $metaCoreBuilder = Builder::createDefault();
        /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\EnumType $type */
        $this->type = $metaCoreBuilder->buildForClass(new \ReflectionClass(xxxMetaCoreUnitTestEnumxxx::class));
    }

    public function testGeneralBuilderResult()
    {
        static::assertInstanceOf(MetaCore\Type\EnumType::class, $this->type, 'Must be recognized as en enum');
    }

    public function testEnum()
    {
        static::assertEquals(
            xxxMetaCoreUnitTestEnumxxx::class,
            $this->type->getId(),
            'The type id must be correct'
        );
    }

    public function testEnumValues()
    {
        static::assertEquals(
            ['ONE', 'TWO', 'THREE'],
            $this->type->getValues(),
            'The enum values must be recognized correctly'
        );
    }
}
