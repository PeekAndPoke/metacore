<?php
/**
 * Created by IntelliJ IDEA.
 * User: gerk
 * Date: 10.05.17
 * Time: 09:22
 */
declare(strict_types=1);

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\Stubs\xxxMetaCoreUnitTestEnumXxx;
use PeekAndPoke\Component\Slumber\Annotation\Slumber\Polymorphic;
use PHPUnit\Framework\TestCase;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeTest extends TestCase
{
    /** @var Polymorphic */
    private static $polymorphic;

    public static function setUpBeforeClass()
    {
        AnnotationRegistry::registerLoader(function ($class) {
            return class_exists($class) || interface_exists($class) || trait_exists($class);
        });

        $reader = new AnnotationReader();

        self::$polymorphic = $reader->getClassAnnotation(new \ReflectionClass(Type::class), Polymorphic::class);

        if (self::$polymorphic === null) {
            self::fail('Class ' . Type::class . ' does not have a Slumber\Polymorphic annotation');
        }
    }

    /**
     * @param \ReflectionMethod $creatorMethod
     * @param array             $params
     *
     * @dataProvider provideTestPolymorphicAnnouncesType
     */
    public function testPolymorphicAnnouncesType(\ReflectionMethod $creatorMethod, array $params = [])
    {
        /** @var Type $subject */
        $subject     = $creatorMethod->invokeArgs(null, $params);
        $subjectType = $subject::type();
        $subjectCls  = get_class($subject);

        $this->assertContains(
            $subjectType,
            array_keys(self::$polymorphic->getMapping()),
            'The Slumber\Polymorphic annotation must announce ' . $subjectCls . ' by type ' . $subjectType
        );

        $announcedClass = self::$polymorphic->getMapping()[$subjectType];

        $this->assertSame(
            $subjectCls,
                $announcedClass,
            'The Slumber\Polymorphic annotation must announce ' . $subjectType . ' as ' . $subjectCls . ' but said ' . $announcedClass
        );
    }

    public function provideTestPolymorphicAnnouncesType()
    {
        $reflect = new \ReflectionClass(Type::class);

        return [
            [$reflect->getMethod('any')],
            [$reflect->getMethod('boolean')],
            [$reflect->getMethod('double')],
            [$reflect->getMethod('float')],
            [$reflect->getMethod('int')],
            [$reflect->getMethod('string')],
            [$reflect->getMethod('dateTime')],
            [$reflect->getMethod('localDateTime')],
            [$reflect->getMethod('enum'), [xxxMetaCoreUnitTestEnumXxx::void()]],
            [$reflect->getMethod('object'), ['object']],
            [$reflect->getMethod('list_'), [Type::string()->ref()]],
            [$reflect->getMethod('map'), [Type::string()->ref(), Type::string()->ref()]],
        ];
    }
}
