<?php
/**
 * @author Ernesto Baez
 */

namespace Psr\Repository;


/**
 * Interface RemoteRepositoryInterface
 *
 * @package Psr\Repository
 */
interface RemoteRepositoryInterface
{
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    function all($columns = ['*']);

    /**
     * Find data by id
     *
     * @param int   $id
     * @param array $columns
     *
     * @return mixed
     */
    function find($id, $columns = ['*']);

    /**
     * Find data by field
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return mixed
     */
    function findByField($field, $value, $columns = ['*']);

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    function create(array $attributes);

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return mixed
     */
    function update($id, array $attributes);

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return mixed
     */
    function delete($id);

    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    function setCriteria($criteria);
}
