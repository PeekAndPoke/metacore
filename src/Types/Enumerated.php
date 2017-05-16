<?php
/**
 * Created by gerk on 08.09.16 16:39
 */

namespace PeekAndPoke\Component\Types;

use PeekAndPoke\Component\Psi\Interfaces\Functions\ValueHolderInterface;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
abstract class Enumerated implements ValueHolderInterface
{
    /** @var string */
    private $value;
    /** @var boolean */
    private $valid = true;

    protected function __construct()
    {
        // noop
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * static initializer to set up all enum values
     */
    public static function init()
    {
        $reflect = static::getReflection();
        /** @var array $staticProps */
        $props = $reflect->getStaticProperties();

        foreach ($props as $name => $val) {
            $inst        = new static();
            $inst->value = $name;
            $reflect->setStaticPropertyValue($name, $inst);
        }
    }

    /**
     * Get an instance of the enum with a value of 'null'.
     *
     * This might be handy if we need an instance of the Enum to work with but
     * we do not really care about a real value being set.
     *
     * @return static
     */
    public static function void()
    {
        return static::from(null);
    }

    /**
     * Get Enum for the given input
     *
     * @param string $value
     *
     * @return static
     */
    public static function from($value)
    {
        $value = (string) $value;

        // first we try to find the REAL values
        $enumerated = static::enumerateProps();

        if (array_key_exists($value, $enumerated)) {
            return $enumerated[$value];
        }

        // then we keep track of INVALID values.
        static $invalid = [];
        // take care of the called class
        $cls = get_called_class();

        if (! isset($invalid[$cls][$value])) {

            $inst        = new static();
            $inst->value = $value;
            $inst->valid = false;

            $invalid[$cls][$value] = $inst;
        }

        return $invalid[$cls][$value];
    }

    /**
     * @return string[]
     */
    public static function enumerateValues()
    {
        return array_keys(static::enumerateProps());
    }

    /**
     * @return static[]
     */
    protected static function enumerateProps()
    {
        return static::getReflection()->getStaticProperties();
    }

    /**
     * @return \ReflectionClass
     */
    private static function getReflection()
    {
        // NOTICE The static field will be initialized by each class individually!
        //        This is different than how static class properties work!

        /** @var \ReflectionClass $reflect */
        static $reflect;

        if ($reflect === null) {
            $reflect = new \ReflectionClass(static::class);
        }

        return $reflect;
    }
}