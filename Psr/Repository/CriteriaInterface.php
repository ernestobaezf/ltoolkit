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
     * Set list of fields that can be used to filter
     *
     * @param array $fields
     */
    function setSearchableFields(array $fields);

    /**
     * Get fields that can be used to filter
     *
     * @return array
     */
    function getSearchableFields(): array;

    /**
     * Apply criteria
     *
     * @param mixed $model
     *
     * @return mixed
     */
    function apply($model);
}
