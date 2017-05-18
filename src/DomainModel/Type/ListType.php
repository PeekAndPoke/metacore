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
class ListType extends Type
{
    public const TYPE = 'List';

    /** @var TypeRef */
    private $valueTypeRef;

    /**
     * ListType constructor.
     *
     * @param TypeRef $valueTypeRef
     */
    public function __construct(TypeRef $valueTypeRef)
    {
        parent::__construct();

        $this->valueTypeRef = $valueTypeRef;
    }

    public static function type() : string
    {
        return self::TYPE;
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
        return new TypeRef($this->getId(), [$this->valueTypeRef]);
    }
}
