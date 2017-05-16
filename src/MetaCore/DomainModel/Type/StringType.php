<?php
/**
 * Created by gerk on 26.09.16 17:31
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class StringType extends Type
{
    public const TYPE = 'String';

    public static function type() : string
    {
        return self::TYPE;
    }
}
