<?php
/**
 * Created by gerk on 01.10.16 17:20
 */

namespace PeekAndPoke\Component\MetaCore;

use PeekAndPoke\Component\MetaCore\DomainModel\Property;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
interface PropertyMapper
{
    /**
     * @param Builder             $builder
     * @param \ReflectionProperty $property
     *
     * @return Property
     */
    public function mapProperty(Builder $builder, \ReflectionProperty $property);
}
