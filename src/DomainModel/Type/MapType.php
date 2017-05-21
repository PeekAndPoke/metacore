<?php
/**
 * Created by gerk on 28.09.16 15:35
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\MetaCore\DomainModel\TypeRef;


/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class MapType extends Type
{
    const TYPE = 'Map';

    /** @var TypeRef */
    private $keyTypeRef;
    /** @var TypeRef */
    private $valueTypeRef;

    /**
     * MapType constructor.
     *
     * @param TypeRef $keyTypeRef
     * @param TypeRef $valueTypeRef
     */
    public function __construct(TypeRef $keyTypeRef, TypeRef $valueTypeRef)
    {
        parent::__construct();

        $this->keyTypeRef   = $keyTypeRef;
        $this->valueTypeRef = $valueTypeRef;
    }

    /**
     * @return string
     */
    public static function type()
    {
        return self::TYPE;
    }

    /**
     * @return TypeRef
     */
    public function getKeyTypeRef()
    {
        return $this->keyTypeRef;
    }

    /**
     * @return TypeRef
     */
    public function getValueTypeRef()
    {
        return $this->valueTypeRef;
    }

    /**
     * @return TypeRef
     */
    public function ref()
    {
        return new TypeRef($this->getId(), [$this->keyTypeRef, $this->valueTypeRef]);
    }
}
