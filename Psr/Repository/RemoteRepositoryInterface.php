<?php
/**
 * @author Ernesto Baez
 */

namespace Psr\Repository;


/**
 * Interface RemoteRepositoryInterface
 *
 * @package LToolkit\Interfaces
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
    public function all($columns = ['*']);

    /**
     * Find data by id
     *
     * @param int   $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Find data by field
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*']);

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    public function setCriteria($criteria);
}
