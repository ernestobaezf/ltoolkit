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
     * @inheritDoc
     */
    public function cast(): PrettusCriteriaInterface
    {
        $result = new RequestCriteria(request());
        return $result;
    }

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
     * Set list of fields that can be used to filter
     *
     * @param array $fields
     *
     * @throws Exception this function is not implemented on adapters
     */
    function setSearchableFields(array $fields)
    {
        throw new Exception("Not implemented");
    }

    /**
     * Get list of fields that can be used to filter
     *
     * @return array
     *
     * @throws Exception this function is not implemented on adapters
     */
    function getSearchableFields(): array
    {
        throw new Exception("Not implemented");
    }
}
