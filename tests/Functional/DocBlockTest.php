<?php
/**
 * Created by gerk on 04.10.16 16:48
 */

namespace PeekAndPoke\Component\MetaCore\Functional;

use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DocBlockTest extends TestCase
{
    /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType */
    private $type;

    public function setUp()
    {
        $metaCoreBuilder = Builder::createDefault();
        /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType $type */
        $this->type = $metaCoreBuilder->buildForClass(new \ReflectionClass(__MetaCoreDocBlockTestSubject::class));
    }

    public function testGeneralBuilderResult()
    {
        static::assertInstanceOf(MetaCore\Type\ObjectType::class, $this->type, 'Must be an instance of ObjectType');
    }

    /**
     * @param string $propertyName
     *
     * @dataProvider provideTestPropertyDocBlock
     */
    public function testPropertyDocBlock($propertyName)
    {
        $def = $this->type->getPropertyByName($propertyName);

        static::assertEquals(
            'This is ' . $propertyName,
            $def->getDoc()->getSummary(),
            'The doc-block summary for the property must be correct'
        );

        static::assertEquals(
            'And the desc of' . "\n" . $propertyName . " ...\n\n" . 'plus additions',
            $def->getDoc()->getDescription(),
            'The doc-block description for the property must be correct'
        );
    }

    /**
     * @return array
     */
    public static function provideTestPropertyDocBlock()
    {
        return [
            ['propOne'],
            ['propTwo'],
        ];
    }
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreDocBlockTestSubject
{
    /**
     * This is propOne
     *
     * And the desc of
     * propOne ...
     *
     * plus additions
     *
     * @var int
     */
    public $propOne = 0;

    /**
     * This is propTwo
     *
     * And the desc of
     * propTwo ...
     *
     * plus additions
     *
     * @var int
     */
    public $propTwo = 0;
}
