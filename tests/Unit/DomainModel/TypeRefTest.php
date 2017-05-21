<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 11.05.17
 * Time: 17:26
 */

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRef;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeRefTest extends TestCase
{

    public function testConstruction()
    {
        $subject = new TypeRef('id', [Type::any()->ref(), Type::string()->ref()]);

        self::assertSame('id', $subject->getId(), 'The id must be set correctly');
    }

    public function testGetNumParams()
    {
        $subject = new TypeRef('id', [Type::any()->ref(), Type::string()->ref()]);

        self::assertSame(2, $subject->getNumParams(), 'The number of parameters must be correct');
    }

    public function testGetParams()
    {
        $params = [Type::any()->ref(), Type::string()->ref()];

        $subject = new TypeRef('id', $params);

        self::assertSame($params, $subject->getParams(), 'The params must be set correctly');
    }
}
