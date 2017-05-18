<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\EnumType;
use PeekAndPoke\Component\MetaCore\Stubs\xxxMetaCoreUnitTestEnumXxx;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class EnumTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(EnumType::TYPE, EnumType::type());
    }

    public function testConstruction()
    {
        $subject = new EnumType(xxxMetaCoreUnitTestEnumXxx::void());

        $this->assertSame(
            ['ONE', 'TWO', 'THREE'],
            $subject->getValues(),
            'The values must be set correctly'
        );
    }

    public function testGetIdWhenAliasIsNotSpecified()
    {
        $type = new EnumType(xxxMetaCoreUnitTestEnumXxx::void());

        $this->assertSame(
            xxxMetaCoreUnitTestEnumXxx::class,
            $type->getId(),
            'The id must match the Fqcn if no alias is given'
        );
    }

    public function testGetIdWhenAliasIsSpecified()
    {
        $type = new EnumType(xxxMetaCoreUnitTestEnumXxx::void(), 'enum.alias');

        $this->assertSame(
            'enum.alias',
            $type->getId(),
            'The id must match the specified alias'
        );
    }
}
