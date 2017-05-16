<?php
/**
 * Created by gerk on 01.10.16 17:20
 */

namespace PeekAndPoke\Component\MetaCore;

use PeekAndPoke\Component\MetaCore\DomainModel\Property;
use phpDocumentor\Reflection\DocBlock;

/**
 * IPropertyMapper
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
interface IPropertyMapper
{
    /**
     * @param Builder             $builder
     * @param \ReflectionProperty $property
     * @param DocBlock            $docBlock
     *
     * @return Property
     */
    public function mapProperty(Builder $builder, \ReflectionProperty $property, DocBlock $docBlock);
}
