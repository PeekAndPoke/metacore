<?php
/**
 * Created by gerk on 01.10.16 17:18
 */

namespace PeekAndPoke\Component\MetaCore;


/**
 * IPropertyFilter
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
interface IPropertyFilter
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return bool
     */
    public function filterProperty(\ReflectionProperty $property);
}
