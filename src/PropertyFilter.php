<?php
/**
 * Created by gerk on 01.10.16 17:18
 */

namespace PeekAndPoke\Component\MetaCore;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
interface PropertyFilter
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return bool
     */
    public function filterProperty(\ReflectionProperty $property);
}
