<?php
/**
 * Created by gerk on 27.09.16 17:01
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel;

use PeekAndPoke\Component\Slumber\Annotation\Slumber;

/**
 * TypeRegistry
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeRegistry
{
    /**
     * @var Type[]
     *
     * @Slumber\AsMap(
     *     @Slumber\AsObject(Type::class)
     * )
     */
    private $types = [];

    /**
     * @param string $id
     * @param Type   $type
     *
     * @return $this
     */
    public function add($id, Type $type)
    {
        $this->types[$id] = $type;

        ksort($this->types);

        return $this;
    }

    /**
     * @return Type[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param string $id
     *
     * @return null|Type
     */
    public function getById($id)
    {
        return isset($this->types[$id]) ? $this->types[$id] : null;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasById($id)
    {
        return isset($this->types[$id]);
    }
}
