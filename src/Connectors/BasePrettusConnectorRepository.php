<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Connectors;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use l5toolkit\Interfaces\IRepositoryConnector;
use Prettus\Repository\Eloquent\BaseRepository as PrettusRepositoryEloquent;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Exceptions\RepositoryException;

class BasePrettusConnectorRepository extends PrettusRepositoryEloquent implements IRepositoryConnector
{
    private $modelClass;

    public function __construct(Application $app, string $modelClass)
    {
        $this->modelClass = $modelClass;
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return $this->modelClass;
    }

    public function find($id, $columns = ['*'])
    {
        try {
            return parent::find($id, $columns);
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    public function update(array $attributes, $id)
    {
        try {
            return parent::update($attributes, $id);
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    public function findByField($field, $value=null, $columns = ['*'])
    {
        try {
            return parent::findByField($field, $value, $columns);
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    public function getFieldsSearchable()
    {
        return $this->model->getFillable();
    }

    /**
     * @param  int $id
     * @return int|mixed
     * @throws RepositoryException
     */
    public function delete($id)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->find($id);

        if (!$model) {
            return 0;
        }

        $originalModel = clone $model;

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        $deleted = $model->delete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }
}
