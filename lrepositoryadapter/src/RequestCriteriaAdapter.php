<?php
/**
 * User: Ernesto Baez <ernesto.baez@cdev.global>
 * Date: 02/12/19 11:04 AM
 */

namespace LRepositoryAdapter;


use Exception;
use Prettus\Repository\Criteria\RequestCriteria;
use LRepositoryAdapter\Interfaces\CriteriaAdapterInterface;
use Prettus\Repository\Contracts\CriteriaInterface as PrettusCriteriaInterface;

class RequestCriteriaAdapter implements CriteriaAdapterInterface
{
    /**
     * Apply criteria
     *
     * @param mixed $model
     *
     * @return mixed
     * @throws Exception this function is not implemented on adapters
     */
    function apply($model)
    {
        throw new Exception("Not implemented");
    }

    /**
     * @inheritDoc
     */
    public function cast(): PrettusCriteriaInterface
    {
        $result = new RequestCriteria(request());
        return $result;
    }
}
