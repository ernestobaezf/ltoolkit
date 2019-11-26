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
     * @param mixed ...$params
     *
     * @return mixed
     */
    function apply(...$params);
}
