<?php
/**
 * @author Ernesto Baez 
 */

namespace LRepositoryAdapter;

use Illuminate\Container\Container as Application;
use LRepositoryAdapter\Interfaces\RepositoryAdapterInterface;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;

class BasePrettusRepositoryAdapter extends PrettusBaseRepository implements RepositoryAdapterInterface
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

    /**
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->model->getFillable();
    }

    public function pushCriteria($criteria)
    {
        return parent::pushCriteria($criteria);
    }
}
