<?php
/**
 * Created by gerk on 05.10.16 11:50
 */

namespace PeekAndPoke\Component\MetaCore\Tests\Functional;

use PeekAndPoke\Component\MetaCore\Builder;
use PeekAndPoke\Component\MetaCore\Exception\MetaCoreRuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class ErrorsTest extends TestCase
{
    /**
     * @param MetaCoreRuntimeException $exception
     * @param int                      $expectedCode
     */
    public static function assertRootCause(MetaCoreRuntimeException $exception, $expectedCode)
    {
        $realCode = $exception->getMetaCoreRootCause()->getCode();

        static::assertEquals(
            $expectedCode,
            $realCode,
            "The expected exception code $expectedCode does not match given code $realCode \n" .
            'Exceptions: ' . $exception->getCausesAsString() . "\n" .
            "Details: \n" . $exception->toString()
        );
    }

    /**
     */
    public function testNoVarTagError()
    {
        try {
            $builder = Builder::createDefault();
            $builder->buildForClass(new \ReflectionClass(__MetaCoreErrorsTestForMissingVarTag::class));

            static::fail('Must throw an exception');

        } catch (MetaCoreRuntimeException $e) {

            static::assertRootCause($e, MetaCoreRuntimeException::NO_VAR_TAG);
        }
    }

    public function testInvalidTypeInVarTag()
    {
        try {
            $builder = Builder::createDefault();
            $builder->buildForClass(new \ReflectionClass(__MetaCoreErrorsTestForInvalidTypeInVarTag::class));

            static::fail('Must throw an exception');

        } catch (MetaCoreRuntimeException $e) {

            static::assertRootCause($e, MetaCoreRuntimeException::UNKNOWN_TYPE);
        }
    }

    public function testNullsOnlyCompoundTypeInVarTag()
    {
        try {
            $builder = Builder::createDefault();
            $builder->buildForClass(new \ReflectionClass(__MetaCoreErrorsNullsOnlyCompoundTypeInVarTag::class));

            static::fail('Must throw an exception');

        } catch (MetaCoreRuntimeException $e) {

            static::assertRootCause($e, MetaCoreRuntimeException::COMPOUND_TYPE_WITH_NULLS_ONLY);
        }
    }
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreErrorsTestForMissingVarTag
{
    /**
     * No var tag here
     */
    public $noVarTag;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreErrorsTestForInvalidTypeInVarTag
{
    /** @noinspection PhpUndefinedClassInspection */
    /**
     * @var invalidThing
     */
    public $propWithInvalidVarTag;
}

/**
 * @internal
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class __MetaCoreErrorsNullsOnlyCompoundTypeInVarTag
{
    /**
     * @var null|null
     */
    public $nullOnlyCompoundType;
}
