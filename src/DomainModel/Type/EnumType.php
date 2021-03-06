<?php
/**
 * Created by gerk on 27.09.16 17:24
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel\Type;

use PeekAndPoke\Component\MetaCore\DomainModel\Type;
use PeekAndPoke\Component\Slumber\Annotation\Slumber;
use PeekAndPoke\Types\Enumerated;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class EnumType extends Type
{
    const TYPE = 'Enum';

    /**
     * @var string
     *
     * @Slumber\AsString()
     */
    protected $id;

    /**
     * @var string[]
     *
     * @Slumber\AsList(
     *     @Slumber\AsString()
     * )
     */
    protected $values = [];

    /**
     * @param Enumerated  $enum  The enum class represented
     * @param null|string $alias The alias name
     */
    public function __construct(Enumerated $enum, $alias = null)
    {
        parent::__construct();

        $this->id     = $alias ?: get_class($enum);
        $this->values = $enum::enumerateValues();
    }

    /**
     * @return string
     */
    public static function type()
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \string[]
     */
    public function getValues()
    {
        return $this->values;
    }
}
