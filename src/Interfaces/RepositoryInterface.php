<?php
namespace LToolkit\Interfaces;


use Exception;
use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 *
 * @package LToolkit\Interfaces
 */
interface RepositoryInterface
{
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*']): Collection;

    /**
     * Retrieve all data of repository, paginated
     *
     * @param ?int  $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param ?int  $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*']);

    /**
     * Find entity by id. Returns null if not found
     *
     * @param int   $id
     * @param array $columns
     *
     * @return null|EntityInterface
     *
     * @throws Exception Not found exception
     */
    public function find($id, $columns = ['*']): ?EntityInterface;

    /**
     * Find entity by id. Returns null if not found
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection
     *
     * @throws Exception Not found exception
     */
    public function findByField($field, $value, $columns = ['*']): Collection;

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return EntityInterface
     *
     * @throws Exception
     */
    public function create(array $attributes): EntityInterface;

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return EntityInterface|null
     *
     * @throws Exception
     */
    public function update($id, array $attributes): ?EntityInterface;

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
    public function updateOrCreate(array $attributes, array $values = []): EntityInterface;

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return int
     *
     * @throws Exception Not found exception
     */
    public function delete($id): int;
}
