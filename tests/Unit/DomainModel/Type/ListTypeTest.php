<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\Type\ListType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class ListTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(ListType::TYPE, ListType::type());
    }

    public function testGetId()
    {
        $itemType = Type::any()->ref();
        $subject  = new ListType($itemType);

        $this->assertSame(ListType::TYPE, $subject->getId(), 'The id must be returned correctly');
    }

    public function testConstruction()
    {
        $itemType = Type::any()->ref();
        $subject  = new ListType($itemType);

        $this->assertSame($itemType, $subject->getValueTypeRef(), 'The value type must be set correctly');
    }

    public function testToRefNumParamsEquals1()
    {
        $itemType = Type::any()->ref();
        $subject  = new ListType($itemType);

        $this->assertSame(1, $subject->ref()->getNumParams(), 'The number of type parameters must be exactly 1');
    }

    public function testToRefHasCorrectTypeParam()
    {
        $itemType = Type::any()->ref();
        $subject  = new ListType($itemType);

        $this->assertSame($itemType, $subject->ref()->getParamAt(0), 'The first type parameter must be correct');
    }
}
