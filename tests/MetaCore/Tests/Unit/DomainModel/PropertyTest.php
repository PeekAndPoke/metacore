<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 11.05.17
 * Time: 17:30
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel;

use PeekAndPoke\Component\MetaCore\DomainModel\Docs\Doc;
use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\Visibility;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class PropertyTest extends TestCase
{
    public function testConstruction()
    {
        $subject = new Property(
            $name = 'name',
            $type = Type::any()->ref(),
            $visibility = Visibility::$PROTECTED,
            $nullable = true,
            $doc = new Doc('Sum', 'Desc')
        );

        self::assertSame($name, $subject->getName(), 'The name must be set correctly');

        self::assertSame($type, $subject->getTypeRef(), 'The typeRef must be set correctly');

        self::assertSame($visibility, $subject->getVisibility(), 'The visibility must be set correctly');

        self::assertSame($nullable, $subject->isNullable(), 'The nullability must be set correctly');

        self::assertSame($doc, $subject->getDoc(), 'The doc must be set correctly');
    }

}
