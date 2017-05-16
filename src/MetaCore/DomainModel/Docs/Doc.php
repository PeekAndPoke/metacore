<?php
/**
 * Created by gerk on 29.09.16 15:51
 */

namespace PeekAndPoke\Component\MetaCore\DomainModel\Docs;

use PeekAndPoke\Component\Slumber\Annotation\Slumber;

/**
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class Doc
{
    /**
     * @var string
     *
     * @Slumber\AsString()
     */
    private $summary;

    /**
     * @var string
     *
     * @Slumber\AsString()
     */
    private $description;

    /**
     * Doc constructor.
     *
     * @param string $summary
     * @param string $description
     */
    public function __construct($summary, $description)
    {
        $this->summary     = $summary;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}
