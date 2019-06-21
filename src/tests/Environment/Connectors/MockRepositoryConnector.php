<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Connectors;


use Closure;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRepositoryConnector;

class MockRepositoryConnector implements IRepositoryConnector
{
    public function __construct(Application $app, string $modelClass)
    {

    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return collect();
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        // TODO: Implement paginate() method.
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        // TODO: Implement simplePaginate() method.
    }

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        // TODO: Implement find() method.
    }

    /**
     * Find data by specified field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     *
     * @return Collection
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        // TODO: Implement findByField() method.
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update a entity in repository by id
     *
     * @param int $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Set Presenter
     *
     * @param mixed $presenter
     *
     * @return mixed
     */
    public function setPresenter($presenter)
    {
        // TODO: Implement setPresenter() method.
    }

    /**
     * Skip Presenter Wrapper
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipPresenter($status = true)
    {
        // TODO: Implement skipPresenter() method.
    }

    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     * @throws Exception
     */
    public function pushCriteria($criteria)
    {
        // TODO: Implement pushCriteria() method.
    }

    /**
     * Pop Criteria
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria)
    {
        // TODO: Implement popCriteria() method.
    }

    /**
     * Get Collection of Criteria
     *
     * @return mixed
     */
    public function getCriteria()
    {
        // TODO: Implement getCriteria() method.
    }

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        // TODO: Implement skipCriteria() method.
    }

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        // TODO: Implement resetCriteria() method.
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        // TODO: Implement updateOrCreate() method.
    }

    /**
     * Query Scope
     *
     * @param Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(Closure $scope)
    {
        // TODO: Implement scopeQuery() method.
    }
}