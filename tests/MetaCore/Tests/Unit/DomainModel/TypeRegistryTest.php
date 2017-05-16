<?php
/**
 * Created by gerk on 06.10.16 17:08
 */

namespace PeekAndPoke\Component\MetaCore\Tests\Unit\DomainModel;

use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PHPUnit\Framework\TestCase;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeRegistryTest extends TestCase
{

    public function testGetTypesWhenEmpty()
    {
        $subject = new MetaCore\TypeRegistry();

        static::assertSame([], $subject->getTypes());
    }

    public function testGetTypesAlphabeticallySorted()
    {
        $subject = new MetaCore\TypeRegistry();

        $subject->add('z', $z = new MetaCore\Type\ObjectType('z'));
        $subject->add('a', $a = new MetaCore\Type\ObjectType('a'));
        $subject->add('h', $h = new MetaCore\Type\ObjectType('h'));

        self::assertSame(
            ['a', 'h', 'z'],
            array_keys($subject->getTypes()),
            'The types must be returned in alphabetical order'
        );
    }

    public function testGetAndHasById()
    {
        $subject = new MetaCore\TypeRegistry();

        $subject->add('a', $a = new MetaCore\Type\ObjectType('a'));

        self::assertTrue($subject->hasById($a->getId()), 'hasById must work');
        self::assertSame($a, $subject->getById($a->getId()), 'getById must return the correct object');

        self::assertFalse($subject->hasById('b'), 'hasById must work');
        self::assertNull($subject->getById('b'), 'getById must return null when not found');
    }
}
