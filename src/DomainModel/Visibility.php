<?php
/**
 * Created by gerk on 27.09.16 16:38
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel;

use PeekAndPoke\Types\Enumerated;


/**
 * Visibility
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class Visibility extends Enumerated
{
    /** @var Visibility */
    public static $PUBLIC;

    /** @var Visibility */
    public static $PROTECTED;

    /** @var Visibility */
    public static $PRIVATE;

    /**
     * @param \ReflectionProperty $property
     *
     * @return Visibility
     */
    public static function fromReflection(\ReflectionProperty $property)
    {
        if ($property->isPublic()) {
            return Visibility::$PUBLIC;
        }

        if ($property->isProtected()) {
            return Visibility::$PROTECTED;
        }

        return Visibility::$PRIVATE;
    }
}

Visibility::init();
