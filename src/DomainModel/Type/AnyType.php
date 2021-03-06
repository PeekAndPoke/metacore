<?php
/**
 * Created by gerk on 27.09.16 17:24
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class AnyType extends Type
{
    const TYPE = '*';

    public static function type()
    {
        return self::TYPE;
    }
}
