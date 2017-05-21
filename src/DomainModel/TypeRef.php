<?php
/**
 * Created by gerk on 23.09.16 00:30
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel;

use PeekAndPoke\Component\Slumber\Annotation\Slumber;

/**
 * TypeRef
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class TypeRef
{
    /**
     * @var string
     *
     * @Slumber\AsString()
     */
    protected $id;
    /**
     * @var TypeRef[]
     *
     * @Slumber\AsList(
     *     @Slumber\AsObject(TypeRef::class)
     * )
     */
    private $params;

    /**
     * TypeRef constructor.
     *
     * @param string    $id
     * @param TypeRef[] $params
     */
    public function __construct($id, array $params = [])
    {
        $this->id     = $id;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumParams()
    {
        return count($this->params);
    }

    /**
     * @return TypeRef[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param int $idx
     *
     * @return TypeRef|null
     */
    public function getParamAt($idx)
    {
        return isset($this->params[$idx]) ? $this->params[$idx] : null;
    }
}
