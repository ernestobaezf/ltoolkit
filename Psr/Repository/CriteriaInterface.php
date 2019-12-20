<?php
/**
 * @author Ernesto Baez
 */

namespace Psr\Repository;


/**
 * Interface CriteriaInterface
 *
 * @package Psr\Repository
 */
interface CriteriaInterface
{
    /**
     * Apply criteria
     *
     * @param mixed $model
     *
     * @return mixed
     */
    function apply($model): CriteriaInterface;
}
