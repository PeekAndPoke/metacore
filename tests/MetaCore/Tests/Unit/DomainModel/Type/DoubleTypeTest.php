<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\DoubleType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DoubleTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(DoubleType::TYPE, DoubleType::type());
    }

    public function testGetId()
    {
        $subject = new DoubleType();

        self::assertSame(DoubleType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
