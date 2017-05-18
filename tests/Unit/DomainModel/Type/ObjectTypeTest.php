<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 07:33
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Docs\Doc;
use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType;
use PeekAndPoke\Component\MetaCore\DomainModel\Visibility;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class ObjectTypeTest extends TestCase
{
    public function testType()
    {
        self::assertSame(ObjectType::TYPE, ObjectType::type());
    }

    public function testConstruction()
    {
        $subject = new ObjectType('a');

        $this->assertSame('a', $subject->getId(), 'The id must be set correctly');
    }

    public function testGetIdWhenAliasIsNotSpecified()
    {
        $type = new ObjectType(new Type\AnyType());

        $this->assertSame(
            Type\AnyType::class,
            $type->getId(),
            'The id must match the Fqcn if no alias is given'
        );
    }

    public function testGetIdWhenAliasIsSpecified()
    {
        $type = new ObjectType(new Type\AnyType(), 'object.alias');

        $this->assertSame(
            'object.alias',
            $type->getId(),
            'The id must match the specified alias'
        );
    }

    public function testSetAndGetDoc()
    {
        $subject = new ObjectType(new Type\AnyType());

        $doc = new Doc('Summary', 'Description');
        $subject->setDoc($doc);

        $this->assertSame($doc, $subject->getDoc(), 'The doc must be set correctly');
    }

    public function testGetPropertyByNameSucceeds()
    {
        $subject = new ObjectType('a');

        $prop1 = new Property('prop1', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop1);
        $prop2 = new Property('prop2', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop2);
        $prop3 = new Property('prop3', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop3);

        $this->assertSame($prop1, $subject->getPropertyByName('prop1'), 'Getting a property by name must work correctly');
        $this->assertSame($prop2, $subject->getPropertyByName('prop2'), 'Getting a property by name must work correctly');
        $this->assertSame($prop3, $subject->getPropertyByName('prop3'), 'Getting a property by name must work correctly');
    }

    public function testGetPropertyByNameReturnsNullIfPropertyDoesNotExist()
    {
        $subject = new ObjectType('a');

        $prop1 = new Property('prop1', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop1);
        $prop2 = new Property('prop2', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop2);
        $prop3 = new Property('prop3', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop3);

        $this->assertNull($subject->getPropertyByName('UNKNOWN'), 'Trying to get a non existing property must return null');
    }

    public function testGetPropertiesReturnsEmptyArray()
    {
        $subject = new ObjectType('a');

        self::assertEquals([], $subject->getProperties(), 'getProperties must return an empty array');
    }

    public function testGetProperties()
    {
        $subject = new ObjectType('a');

        $prop1 = new Property('prop1', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop1);
        $prop2 = new Property('prop2', Type::string()->ref(), Visibility::$PUBLIC, false, new Doc('', ''));
        $subject->addProperty($prop2);

        self::assertEquals([$prop1, $prop2], $subject->getProperties(), 'getProperties must work correctly');
    }

    public function testGetExtendsReturnsEmptyArray()
    {
        $subject = new ObjectType('a');

        self::assertEquals([], $subject->getExtends(), 'getExtends must return an empty array');
    }

    public function testGetExtends()
    {
        $subject = new ObjectType('a');

        $ext1 = (new ObjectType('b'))->ref();
        $subject->addExtends($ext1);
        $ext2 = (new ObjectType('b'))->ref();
        $subject->addExtends($ext2);

        self::assertEquals([$ext1, $ext2], $subject->getExtends(), 'getExtends must work correctly');
    }
}
