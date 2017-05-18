<?php
/**
 * Created by gerk on 04.10.16 16:42
 */

namespace PeekAndPoke\Component\MetaCore\Functional;

use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\DomainModel as MetaCore;
use PeekAndPoke\Component\Psi\Psi;
use PHPUnit\Framework\TestCase;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class InheritanceTest extends TestCase
{
    /** @var Builder */
    private static $builder;
    /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType */
    private static $type;

    public function setUp()
    {
        if (static::$builder === null) {

            static::$builder = Builder::createDefault();
            /** @var \PeekAndPoke\Component\MetaCore\DomainModel\Type\ObjectType $type */
            static::$type = static::$builder->buildForClass(new \ReflectionClass(__MetaCoreInheritanceTestSubject::class));
        }
    }

    public function testGeneralBuilderResult()
    {
        static::assertInstanceOf(MetaCore\Type\ObjectType::class, static::$type, 'Must be an instance of ObjectType');
    }

    public function testGetAllProperties()
    {
        $allProperties = static::$builder->getAllProperties(static::$type);

        $expectedNames = [
            'anInt',
            'anIntFromSubjectsTrait',
            'anIntFrom2ndLevelTraitFromSubjects',
            'aParentInt',
            'anIntFromParentsTrait',
        ];

        static::assertEquals(
            $expectedNames,
            Psi::it($allProperties)->map(function (MetaCore\Property $p) { return $p->getName(); })->toArray(),
            'The properties must be correct'
        );
    }

    /**
     * @param string  $propertyName
     * @param boolean $shouldBeIncluded
     * @param string  $message
     *
     * @dataProvider provideTestPropertyInclusionInMetaModel
     */
    public function testPropertyInclusionInMetaModel($propertyName, $shouldBeIncluded, $message)
    {
        static::assertNotNull(
            (new \ReflectionClass(__MetaCoreInheritanceTestSubject::class))->getProperty($propertyName),
            'The property must be found by PHP-Reflection'
        );

        static::assertEquals(
            $shouldBeIncluded,
            static::$type->getPropertyByName($propertyName) !== null,
            $message
        );
    }

    /**
     * @return array
     */
    public static function provideTestPropertyInclusionInMetaModel()
    {
        return [
            [
                'anIntFromSubjectsTrait',
                true,
                'The property MUST appear in the MetaCore model since it was defined by a trait used by the subject class',
                'anIntFrom2ndLevelTraitFromSubjects',
                true,
                'The property MUST appear in the MetaCore model since it was defined by a nested trait used by the subject class',

                'aParentInt',
                false,
                'The property must NOT appear in the MetaCore model since it was defined by a parent class',
                'anIntFromParentsTrait',
                false,
                'The property must NOT appear in the MetaCore model since it was defined by a trait used by a parent class',
            ],
        ];
    }

}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
trait __MetaCoreInheritanceTestTraitUsedBySubjectsParent
{
    /**
     * @var int
     */
    public $anIntFromParentsTrait = 0;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreInheritanceTestSubjectParent
{
    use __MetaCoreInheritanceTestTraitUsedBySubjectsParent;

    /**
     * @var int
     */
    public $aParentInt = 0;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
trait __MetaCoreInheritanceTestTraitUsedTraitUsedBySubject
{
    /**
     * @var int
     */
    public $anIntFrom2ndLevelTraitFromSubjects = 0;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
trait __MetaCoreInheritanceTestTraitUsedBySubject
{
    use __MetaCoreInheritanceTestTraitUsedTraitUsedBySubject;

    /**
     * @var int
     */
    public $anIntFromSubjectsTrait = 0;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreInheritanceTestSubject extends __MetaCoreInheritanceTestSubjectParent
{
    use __MetaCoreInheritanceTestTraitUsedBySubject;

    /**
     * @var int
     */
    public $anInt;
}
