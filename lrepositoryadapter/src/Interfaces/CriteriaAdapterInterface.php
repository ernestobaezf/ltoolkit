<?php
/**
 * @author Ernesto Baez
 */

namespace  LRepositoryAdapter\Interfaces;


use Psr\Repository\CriteriaInterface;
use Prettus\Repository\Contracts\CriteriaInterface as PrettusCriteriaInterface;

/**
 * Interface CriteriaAdapterInterface
 *
 * @package LRepositoryAdapter
 */
interface CriteriaAdapterInterface extends CriteriaInterface
{
    /**
     * Convert from Psr criteria to PrettusCriteria
     *
     * @return PrettusCriteriaInterface
     */
    function cast(): PrettusCriteriaInterface;
}
