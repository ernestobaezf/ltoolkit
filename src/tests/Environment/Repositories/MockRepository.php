<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Repositories;


use Exception;
use Psr\Repository\EntityInterface;
use Illuminate\Support\Collection;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Interfaces\BaseRepositoryInterface;
use LToolkit\Test\Environment\Models\MockModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MockRepository implements BaseRepositoryInterface
{
    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function pushCriteria($instance)
    {
        return $this;
    }

    public function all($columns = ['*']): Collection
    {
        return new Collection(["all"]);
    }

    public function paginate($limit = null, $columns = ['*'])
    {
        return new Collection(["paginate"]);
    }

    public function find($id, $columns = ['*']): ?EntityInterface
    {
        if($id==0){
            throw new ModelNotFoundException();
        }

        $data = new MockModel();
        $data->id = 1;

        return $data;
    }

    public function create(array $attributes): EntityInterface
    {
        $data = new MockModel();
        $data->id = 1;

        foreach ($attributes as $attribute => $value) {
            $data->$attribute = $value;
        }

        return $data;
    }

    public function update($id, array $attributes): ?EntityInterface
    {
        if($id==0){
            throw new ModelNotFoundException();
        }

        $data = new MockModel();
        $data->id = 1;

        foreach ($attributes as $attribute => $value) {
            $data->$attribute = $value;
        }

        return $data;
    }

    public function delete($id): int
    {
        if($id==0){
            throw new ModelNotFoundException();
        }

        return 1;
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
        return null;
    }

    /**
     * Find entity by id. Returns null if not found
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     *
     * @return Collection
     */
    public function findByField($field, $value, $columns = ['*']): Collection
    {
        return collect();
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return EntityInterface
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = []): EntityInterface
    {
        return new MockModel();
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
        return null;
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return collect();
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
        return $this;
    }

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        return $this;
    }
}
