<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Repositories;


use Illuminate\Support\Collection;
use Psrx\Repository\RemoteRepositoryInterface;

class MockRemoteRepository implements RemoteRepositoryInterface
{
    public function setCriteria($instance)
    {
        return $this;
    }

    public function paginate()
    {
        return new Collection(["paginate"]);
    }

    public function create($input){
        return $input;
    }

    public function update($id,$input){
        if($id==0){
            return null;
        }
        return $input;
    }

    public function delete($id){
        if($id==0){
            return null;
        }
        return 1;
    }

    /**
     * Find data by field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*'])
    {
        // TODO: Implement findByField() method.
    }

    /**
     * Pop Criteria
     *
     * @param mixed $criteria
     *
     * @return mixed
     */
    public function popCriteria($criteria)
    {
        // TODO: Implement popCriteria() method.
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
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
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        // TODO: Implement all() method.
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
}
