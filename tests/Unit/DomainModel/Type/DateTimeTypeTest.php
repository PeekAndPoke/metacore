<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\DateTimeType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DateTimeTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(DateTimeType::TYPE, DateTimeType::type());
    }

    public function testGetId()
    {
        $subject = new DateTimeType();

        self::assertSame(DateTimeType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
