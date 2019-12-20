<?php
namespace Psr\Repository;


use Exception;

/**
 * Interface RepositoryInterface
 *
 * @package Psr\Repository
 */
interface RepositoryInterface
{
    /**
     * Retrieve all data based on the filter criteria (see setCriteria)
     *
     * @param array $columns
     *
     * @return iterable
     */
    function all(array $columns = ['*']): iterable;

    /**
     * Retrieve paginated data based on the filter criteria (see setCriteria)
     *
     * @param int   $limit Amount of elements per page. By default 0 which is no limit
     * @param array $columns
     *
     * @return object
     */
    function paginate(int $limit = 0, array $columns = ['*']);

    /**
     * Find entity by id. Returns null if not found
     *
     * @param string|int $id
     * @param array      $columns
     *
     * @return EntityInterface
     *
     * @throws Exception Not found exception
     */
    function find($id, array $columns = ['*']): EntityInterface;

    /**
     * Find entity by id. Returns null if not found
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return iterable
     *
     * @throws Exception Not found exception
     */
    function findByField($field, $value, $columns = ['*']): iterable;

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return EntityInterface
     *
     * @throws Exception
     */
    function create(array $attributes): EntityInterface;

    /**
     * Update a entity in repository by id
     *
     * @param string|int $id
     * @param array      $attributes
     *
     * @return EntityInterface
     *
     * @throws Exception Not found exception
     */
    function update($id, array $attributes): EntityInterface;

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return EntityInterface
     *
     * @throws Exception
     */
    function updateOrCreate(array $attributes, array $values = []): EntityInterface;

    /**
     * Delete a entity in repository by id
     *
     * @param string|int $id
     *
     * @throws Exception Not found exception
     */
    function delete($id): void;

    /**
     * Push Criteria to filter the query
     *
     * @param iterable iterable<CriteriaInterface> $criteria
     *
     * @return $this
     */
    function setCriteria(iterable $criteria);
}
