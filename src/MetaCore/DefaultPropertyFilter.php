<?php
/**
 * Created by gerk on 01.10.16 17:21
 */

namespace PeekAndPoke\Component\MetaCore;


/**
 * DefaultPropertyFilter
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class DefaultPropertyFilter implements IPropertyFilter
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return bool
     */
    public function filterProperty(\ReflectionProperty $property)
    {
        return true;
    }
}
