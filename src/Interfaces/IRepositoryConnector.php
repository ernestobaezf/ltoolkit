<?php
namespace LToolkit\Interfaces;


use Closure;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface IRepositoryConnector
 *
 * @package LToolkit\Interfaces
 */
interface IRepositoryConnector
{
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*']);

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
     * Find data by id
     *
     * @param int   $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Find data by specified field
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection
     */
    public function findByField($field, $value=null, $columns = ['*']);

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
    public function update(array $attributes, $id);

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Set Presenter
     *
     * @param mixed $presenter
     *
     * @return mixed
     */
    public function setPresenter($presenter);

    /**
     * Skip Presenter Wrapper
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipPresenter($status = true);

    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     * @throws Exception
     */
    public function pushCriteria($criteria);

    /**
     * Pop Criteria
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria);

    /**
     * Get Collection of Criteria
     *
     * @return mixed
     */
    public function getCriteria();

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria();

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = []);

    /**
     * Query Scope
     *
     * @param Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(Closure $scope);
}
