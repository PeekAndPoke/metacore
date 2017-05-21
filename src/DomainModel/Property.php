<?php
/**
 * Created by gerk on 22.09.16 17:03
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel;

use PeekAndPoke\Component\Slumber\Annotation\Slumber;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class Property
{
    /**
     * @var string
     *
     * @Slumber\AsString()
     */
    protected $name;
    /**
     * @var TypeRef
     *
     * @Slumber\AsObject(TypeRef::class)
     */
    protected $typeRef;
    /**
     * @var Visibility
     *
     * @Slumber\AsEnum(Visibility::class)
     */
    protected $visibility;
    /**
     * @var boolean
     *
     * @Slumber\AsBool()
     */
    protected $nullable = false;
    /**
     * @var Docs\Doc
     *
     * @Slumber\AsObject(Docs\Doc::class)
     */
    protected $doc;

    /**
     * @param string     $name
     * @param TypeRef    $typeRef
     * @param Visibility $visibility
     * @param bool       $nullable
     * @param Docs\Doc   $doc
     */
    public function __construct($name, TypeRef $typeRef, Visibility $visibility, $nullable, Docs\Doc $doc)
    {
        $this->name       = $name;
        $this->typeRef    = $typeRef;
        $this->visibility = $visibility;
        $this->nullable   = $nullable;
        $this->doc        = $doc;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return TypeRef
     */
    public function getTypeRef()
    {
        return $this->typeRef;
    }

    /**
     * @return Visibility
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @return Docs\Doc
     */
    public function getDoc()
    {
        return $this->doc;
    }
}
