<?php
namespace ltoolkit\Interfaces;


use Exception;
use Illuminate\Support\Collection;

/**
 * Interface IRepository
 *
 * @package ltoolkit\Interfaces
 */
interface IRepository
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
     * @param null  $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param null  $limit
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
     * @return null|IEntity
     */
    public function find($id, $columns = ['*']): ?IEntity;

    /**
     * Find entity by id. Returns null if not found
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection
     */
    public function findByField($field, $value, $columns = ['*']): Collection;

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return IEntity
     * @throws Exception
     */
    public function create(array $attributes): IEntity;

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return IEntity|null
     * @throws Exception
     */
    public function update($id, array $attributes): ?IEntity;

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return IEntity
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = []): IEntity;

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id): int;
}
