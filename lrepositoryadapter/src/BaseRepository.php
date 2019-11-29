<?php
/**
 * @author Ernesto Baez
 */

namespace LRepositoryAdapter;


use Exception;
use Traversable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Psr\Repository\EntityInterface;
use Psr\Repository\UnitOfWorkInterface;
use Psr\Repository\UoWRepositoryInterface;
use LRepositoryAdapter\Interfaces\RepositoryAdapterInterface;

abstract class BaseRepository implements UoWRepositoryInterface
{
    /**
     * @var RepositoryAdapterInterface
     */
    private $innerRepository;

    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->innerRepository = app()->make(RepositoryAdapterInterface::class, ["modelClass" => $this->model()]);

        $this->unitOfWork = $unitOfWork;
    }

    /**
     * Specify Model class name used in the connector
     *
     * @return string
     */
    protected abstract function model(): string;

    /**
     * @return RepositoryAdapterInterface
     */
    protected function getInternalRepository()
    {
        return $this->innerRepository;
    }

    /**
     * @return UnitOfWorkInterface
     */
    public function unitOfWork(): UnitOfWorkInterface
    {
        return $this->unitOfWork;
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return Traversable
     */
    public function all($columns = ['*']): Traversable
    {
        return $this->getInternalRepository()->all($columns);
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param int|null $limit
     * @param array    $columns
     *
     * @return object
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        return $this->getInternalRepository()->paginate($limit, $columns);
    }

    /**
     * Find entity by id. Returns null if not found
     *
     * @param int   $id
     * @param array $columns
     *
     * @return EntityInterface
     */
    public function find($id, $columns = ['*']): EntityInterface
    {
        return $this->getInternalRepository()->find($id, $columns);

    }

    /**
     * Find data by specified field
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $columns
     *
     * @return Traversable
     */
    public function findByField($field, $value=null, $columns = ['*']): Traversable
    {
        return $this->getInternalRepository()->findByField($field, $value, $columns);
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return EntityInterface
     * @throws Exception
     */
    public function create(array $attributes): EntityInterface
    {
        $this->unitOfWork()->beginTransaction();
        return $this->execute('create', $attributes);
    }

    /**
     * Update a entity in repository by id
     *
     * @param int   $id
     * @param array $attributes
     *
     * @return EntityInterface
     * @throws Exception
     */
    public function update($id, array $attributes): EntityInterface
    {
        $this->unitOfWork()->beginTransaction();
        return $this->execute('update', $attributes, $id);
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
        $this->unitOfWork()->beginTransaction();
        return $this->execute('updateOrCreate', $attributes, $values);
    }

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @throws Exception
     */
    public function delete($id): void
    {
        $this->unitOfWork()->beginTransaction();
        try {
            $this->getInternalRepository()->delete($id);
        } catch (Exception $exception) {
            $this->unitOfWork()->rollback();

            throw $exception;
        }
    }

    /**
     * Push Criteria for filter the query
     *
     * @param Traversable $criteria
     *
     * @return $this
     * @throws Exception
     */
    public function setCriteria(Traversable $criteria)
    {
        $this->getInternalRepository()->setCriteria($criteria);

        return $this;
    }

    /**
     * Helper function to execute functions that alter database and handle relations in one place
     *
     * @param string    $operation
     * @param array     $attributes
     * @param int|array $values
     *
     * @return EntityInterface
     *
     * @throws Exception
     */
    protected final function execute(string $operation, array $attributes, $values=0): EntityInterface
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
            $this->unitOfWork()->rollback();

            throw $exception;
        }
    }

    /**
     * Verify whether the model relations are in the attributes and set autocommit to false if so, to save the data for
     * the model and the relation on one transaction
     *
     * @param array $attributes
     *
     * @return array
     */
    private final function checkRelation(array $attributes): array
    {
        $uow = $this->unitOfWork();
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
        $uow = $this->unitOfWork();

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
}
