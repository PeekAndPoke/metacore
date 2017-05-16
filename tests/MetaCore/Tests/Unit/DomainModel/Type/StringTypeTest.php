<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\StringType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class StringTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(StringType::TYPE, StringType::type());
    }

    public function testGetId()
    {
        $subject = new StringType();

        self::assertSame(StringType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
