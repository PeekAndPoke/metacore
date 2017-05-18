<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\BooleanType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class BooleanTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(BooleanType::TYPE, BooleanType::type());
    }

    public function testGetId()
    {
        $subject = new BooleanType();

        self::assertSame(BooleanType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
