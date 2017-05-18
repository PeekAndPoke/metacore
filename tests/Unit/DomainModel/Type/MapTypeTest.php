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
use PeekAndPoke\Component\MetaCore\DomainModel\Type\MapType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class MapTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(MapType::TYPE, MapType::type());
    }

    public function testGetId()
    {
        $keyType  = Type::string()->ref();
        $itemType = Type::any()->ref();
        $subject  = new MapType($keyType, $itemType);

        $this->assertSame(MapType::TYPE, $subject->getId(), 'The id must be returned correctly');
    }

    public function testConstruction()
    {
        $keyType  = Type::string()->ref();
        $itemType = Type::any()->ref();
        $subject  = new MapType($keyType, $itemType);

        $this->assertSame($keyType,  $subject->getKeyTypeRef(), 'The key type must be set correctly');
        $this->assertSame($itemType, $subject->getValueTypeRef(), 'The value type must be set correctly');
    }

    public function testToRefNumParamsEquals2()
    {
        $keyType  = Type::string()->ref();
        $itemType = Type::any()->ref();
        $subject  = new MapType($keyType, $itemType);

        $this->assertSame(2, $subject->ref()->getNumParams(), 'The number of type parameters must be exactly 2');
    }

    public function testToRefHasCorrectTypeParam()
    {
        $keyType  = Type::string()->ref();
        $itemType = Type::any()->ref();
        $subject  = new MapType($keyType, $itemType);

        $this->assertSame($keyType, $subject->ref()->getParamAt(0), 'The first type parameter must be correct');
        $this->assertSame($itemType, $subject->ref()->getParamAt(1), 'The second type parameter must be correct');
    }
}
