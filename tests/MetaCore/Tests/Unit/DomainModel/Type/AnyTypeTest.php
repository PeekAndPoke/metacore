<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type\AnyType;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class AnyTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(AnyType::TYPE, AnyType::type());
    }

    public function testGetId()
    {
        $subject = new AnyType();

        self::assertSame(AnyType::TYPE, $subject->getId(), 'The id must be correct');
    }
}
