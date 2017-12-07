<?php
/**
 * Created by gerk on 29.09.16 10:33
 */

namespace PeekAndPoke\Component\MetaCore\Exception;

use PeekAndPoke\Component\Toolbox\ExceptionUtil;


/**
 * MetaCoreException
 *
 * @author Karsten J. Gerber <kontakt@karsten-gerber.de>
 */
class MetaCoreRuntimeException extends \RuntimeException
{
    const UNKNOWN_TYPE                  = 1000;
    const NO_VAR_TAG                    = 1001;
    const COMPOUND_TYPE_WITH_NULLS_ONLY = 1002;

    /**
     * @param \Exception|null $previous
     *
     * @return MetaCoreRuntimeException
     */
    public static function unknownType(\Exception $previous = null)
    {
        return new static('Unknown type found', static::UNKNOWN_TYPE, $previous);
    }

    /**
     * @param \Exception|null $previous
     *
     * @return MetaCoreRuntimeException
     */
    public static function noVarTagFound(\Exception $previous = null)
    {
        return new static('No @var tag found', static::NO_VAR_TAG, $previous);
    }

    /**
     * @param \Exception|null $previous
     *
     * @return MetaCoreRuntimeException
     */
    public static function compoundTypeWithNullsOnly(\Exception $previous = null)
    {
        return new static('Only found "null" in compound type', static::COMPOUND_TYPE_WITH_NULLS_ONLY, $previous);
    }

    /**
     * Find the meta core root cause
     *
     * Walks through all previous Exception and find the the last in row that is a MetaCoreRuntimeException as well.
     *
     * @return MetaCoreRuntimeException
     */
    public function getMetaCoreRootCause()
    {
        $root = $this;

        while ($root->getPrevious() && $root->getPrevious() instanceof self) {
            $root = $root->getPrevious();
        }

        return $root;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return ExceptionUtil::toString($this);
    }

    /**
     * @return string
     */
    public function getCausesAsString()
    {
        $ret     = '';
        $current = $this;

        while ($current) {
            $ret .= $current->getMessage() . ' [' . $current->getCode() . ', ' . get_class($current) . ']' . "\n";
            $current = $current->getPrevious();
        }

        return $ret;
    }
}
