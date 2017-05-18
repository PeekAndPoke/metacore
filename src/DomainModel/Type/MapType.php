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
    public const TYPE = 'Map';

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

    public static function type() : string
    {
        return self::TYPE;
    }

    /**
     * @return TypeRef
     */
    public function getKeyTypeRef() : TypeRef
    {
        return $this->keyTypeRef;
    }

    /**
     * @return TypeRef
     */
    public function getValueTypeRef() : TypeRef
    {
        return $this->valueTypeRef;
    }

    /**
     * @return TypeRef
     */
    public function ref() : TypeRef
    {
        return new TypeRef($this->getId(), [$this->keyTypeRef, $this->valueTypeRef]);
    }
}
