<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Repositories;

use Closure;
use Exception;
use Illuminate\Support\Str;
use LToolkit\Traits\TLogAction;
use LToolkit\Interfaces\IEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Interfaces\IBaseRepository;
use LToolkit\Interfaces\IRepositoryAdapter;

abstract class BaseRepository implements IBaseRepository
{
    use TLogAction;

    /**
     * @var IRepositoryAdapter
     */
    private $innerRepository;

    private $unitOfWork;

    public function __construct(IUnitOfWork $unitOfWork)
    {
        $this->innerRepository = app()->make(IRepositoryAdapter::class, ["modelClass" => $this->model()]);

        $this->unitOfWork = $unitOfWork;
    }

    /**
     * Specify Model class name used in the connector
     *
     * @return string
     */
    protected abstract function model(): string;

    /**
     * @return IRepositoryAdapter
     */
    protected function getInternalRepository()
    {
        return $this->innerRepository;
    }

    /**
     * @return IUnitOfWork
     */
    protected function getUnitOfWork()
    {
        return $this->unitOfWork;
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*']): Collection
    {
        return $this->evaluate(
            function () use ($columns) {
                return $this->getInternalRepository()->all($columns);
            },
            __FUNCTION__
        );
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null $limit
     * @param array    $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        return $this->evaluate(
            function () use ($limit, $columns) {
                return $this->getInternalRepository()->paginate($limit, $columns);
            },
            __FUNCTION__,
            ["limit" => $limit]
        );
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param int|null $limit
     * @param array    $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->evaluate(
            function () use ($limit, $columns) {
                return $this->getInternalRepository()->simplePaginate($limit, $columns);
            },
            __FUNCTION__,
            ["limit" => $limit]
        );
    }

    /**
     * Find entity by id. Returns null if not found
     *
     * @param int   $id
     * @param array $columns
     *
     * @return null|IEntity
     */
    public function find($id, $columns = ['*']): ?IEntity
    {
        return $this->evaluate(
            function () use ($id, $columns) {
                $columns = $this->setScope($columns);

                return $this->getInternalRepository()->find($id, $columns);
            },
            __FUNCTION__,
            ["id" => $id]
        );
    }

    /**
     * Find data by specified field
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Collection
     */
    public function findByField($field, $value=null, $columns = ['*']): Collection
    {
        return $this->evaluate(
            function () use ($field, $value, $columns) {
                $columns = $this->setScope($columns);

                return $this->getInternalRepository()->findByField($field, $value, $columns);
            },
            __FUNCTION__,
            ["field" => $field, "value" => $value]
        );
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return IEntity
     * @throws Exception
     */
    public function create(array $attributes): IEntity
    {
        return $this->evaluate(
            function () use ($attributes) {
                $this->getUnitOfWork()->beginTransaction();

                return $this->execute('create', $attributes);
            },
            __FUNCTION__,
            ["attributes" => $attributes]
        );
    }

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return IEntity|null
     * @throws Exception
     */
    public function update($id, array $attributes): ?IEntity
    {
        return $this->evaluate(
            function () use ($attributes, $id) {
                $this->getUnitOfWork()->beginTransaction();

                return $this->execute('update', $attributes, $id);
            },
            __FUNCTION__,
            ["attributes" => $attributes, "id" => $id]
        );
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return IEntity
     * @throws Exception
     */
    public function updateOrCreate(array $attributes, array $values = []): IEntity
    {
        return $this->evaluate(
            function () use ($attributes, $values) {
                $this->getUnitOfWork()->beginTransaction();

                return $this->execute('updateOrCreate', $attributes, $values);
            },
            __FUNCTION__,
            ["attributes" => $attributes, "values" => $values]
        );
    }

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return int
     * @throws Exception
     */
    public function delete($id): int
    {
        return $this->evaluate(
            function () use ($id) {
                $this->getUnitOfWork()->beginTransaction();

                return $this->getInternalRepository()->delete($id);
            },
            __FUNCTION__,
            ["id" => $id]
        );
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
        try
        {
            $this->getInternalRepository()->pushCriteria($criteria);

            return $this;
        } catch (Exception $exception) {
            throw $exception;
        }
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
        return $this->getInternalRepository()->popCriteria($criteria);
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->getInternalRepository()->getCriteria();
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
        $this->getInternalRepository()->skipCriteria($status);

        return $this;
    }

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->getInternalRepository()->resetCriteria();

        return $this;
    }

    /**
     * Query Scope
     *
     * @param Closure $scope
     *
     * @return $this
     */
    protected final function scopeQuery(Closure $scope)
    {
        $this->getInternalRepository()->scopeQuery($scope);

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return array
     */
    protected function setScope(array $columns): array
    {
        $relations = $columns['relations'] ?? null;

        if ($relations) {
            $this->scopeQuery(
                $this->scope($relations)
            );

            unset($columns['relations']);
        }

        return $columns;
    }

    /**
     * Helper function to execute functions that alter database and handle relations in one place
     *
     * @param string    $operation
     * @param array     $attributes
     * @param int|array $values
     *
     * @return IEntity|null
     *
     * @throws Exception
     */
    protected final function execute(string $operation, array $attributes, $values=0): ?IEntity
    {
        try {
            list($autoCommit, $withRelations, $_attributes) = $this->checkRelation($attributes);

            switch ($operation) {
            case 'updateOrCreate':
            case 'update':
                $entity = $this->getInternalRepository()->{$operation}($_attributes, $values);
                break;
            default:
                $entity = $this->getInternalRepository()->create($_attributes);
            }

            foreach ($withRelations as $relation => $value) {
                // Ignore nested relation for the moment
                // todo: implement operations with nested relations
                if (Str::contains($relation, ".")) {
                    continue;
                }

                $entity->{$relation}()->sync($value);

                Log::info(
                    "Sync relation: $relation", [
                    "class" => static::class,
                    "method" => $operation,
                    "payload" => $relation
                    ]
                );
            }

            // If the commit was not set to be automatic then it means the user will take care of commit, so do not
            // commit not reset since it was already false
            if ($autoCommit) {
                $this->commitAndResetUnitOfWork($autoCommit);
            }

            return $entity->load($this->getModelRelations(false));
        } catch (Exception $exception) {
            $this->getUnitOfWork()->rollback();

            throw $exception;
        }
    }

    /**
     * Verify whether the model relations are in the attributes and set autocommit to false if so to save the data for
     * the model and the relation one transaction
     *
     * @param array $attributes
     *
     * @return array
     */
    private final function checkRelation(array $attributes): array
    {
        $uow = $this->getUnitOfWork();
        $autoCommit = $uow->isAutoCommit();

        $_attributes = $attributes;
        $withRelations = [];

        foreach ($this->getModelRelations() as $relation) {
            $value = $attributes[$relation] ?? $attributes[Str::snake($relation)] ?? null;

            if (is_string($value)) {
                $value = json_decode($value);
            }

            if (is_array($value)) {
                $withRelations[$relation] = $value;
            }
        }

        if ($withRelations) {
            $_attributes = array_diff_key($attributes, $withRelations);

            $uow->setAutoCommit(false);
        }

        return array($autoCommit, $withRelations, $_attributes);
    }

    /**
     * Commit and reset unit of work to previous autocommit status
     *
     * @param bool $autoCommit
     */
    private final function commitAndResetUnitOfWork(bool $autoCommit): void
    {
        $uow = $this->getUnitOfWork();

        $uow->commit();

        $uow->setAutoCommit($autoCommit);
    }

    /**
     * Return models relations with other models. Used by repositories to associate entities on CRUD
     *
     * @param bool $clean determines if the result is the clean relation name.
     * (example: -clean- roles or -not clean- roles:id)
     *
     * @return array
     */
    private function getModelRelations(bool $clean=true): array
    {
        $relations = [];

        $_relations = defined($this->model()."::RELATIONS") ? constant($this->model()."::RELATIONS") : [];
        if ($clean && $_relations) {
            foreach ($_relations as $relation) {
                $_relation = explode(":", $relation);

                $relations[] = $_relation[0];
            }
        } else {
            $relations = $_relations;
        }

        return $relations;
    }

    /**
     * Internal helper function
     *
     * @param  mixed    $relations
     * @return Closure
     */
    protected function scope($relations): Closure
    {
        return function ($model) use ($relations) {
            if ($relations) {
                $model = $model->with($relations);
            }

            return $model;
        };
    }
}
