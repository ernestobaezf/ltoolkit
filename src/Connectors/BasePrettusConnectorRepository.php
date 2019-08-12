<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Connectors;

use l5toolkit\Interfaces\IRepositoryConnector;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Eloquent\BaseRepository as PrettusRepositoryEloquent;

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
