<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\IntType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class IntTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(IntType::TYPE, IntType::type());
    }

    public function testGetId()
    {
        $subject = new IntType();

        self::assertSame(IntType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
