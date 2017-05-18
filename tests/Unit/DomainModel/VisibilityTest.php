<?php
/**
 * Created by gerk on 06.10.16 16:26
 */

namespace PeekAndPoke\Component\MetaCore\Unit\DomainModel;

use PeekAndPoke\Component\MetaCore\DomainModel\Visibility;
use PHPUnit\Framework\TestCase;


/**
 * VisibilityTest
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class VisibilityTest extends TestCase
{
    /**
     * test general setup
     */
    public function testIsSetup()
    {
        static::assertNotSame(Visibility::$PRIVATE, Visibility::$PUBLIC, 'Visibility::init() must have been called');
    }

    /**
     * @param string     $propName
     * @param Visibility $expected
     *
     * @dataProvider provideTestFromReflection
     */
    public function testFromReflection($propName, Visibility $expected)
    {
        $prop   = new \ReflectionProperty(__MetaCoreVisibilityFromReflectionTestSubject::class, $propName);
        $result = Visibility::fromReflection($prop);

        static::assertSame($expected, $result, 'The Visibility must be mapped correctly');
    }

    /**
     * @return array
     */
    public static function provideTestFromReflection()
    {
        return [
            [
                'privateProp',
                Visibility::$PRIVATE,
            ],
            [
                'protectedProp',
                Visibility::$PROTECTED,
            ],
            [
                'publicProp',
                Visibility::$PUBLIC,
            ],
        ];
    }
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreVisibilityFromReflectionTestSubject
{
    /**
     * @var mixed
     */
    private $privateProp;

    /**
     * @var mixed
     */
    protected $protectedProp;

    /**
     * @var mixed
     */
    public $publicProp;

    /**
     * @return mixed
     */
    public function getPrivateProp()
    {
        return $this->privateProp;
    }

    /**
     * @return mixed
     */
    public function getProtectedProp()
    {
        return $this->protectedProp;
    }

    /**
     * @return mixed
     */
    public function getPublicProp()
    {
        return $this->publicProp;
    }
}
