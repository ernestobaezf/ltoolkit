<?php
namespace ErnestoBaezF\L5CoreToolbox\Interfaces;

use Illuminate\Support\Collection;

/**
 * Interface IRepositoryCriteria
 *
 * @package ErnestoBaezF\L5CoreToolbox\Interfaces
 */
interface IRepositoryCriteria
{
    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    public function pushCriteria($criteria);

    /**
     * Pop Criteria
     *
     * @param mixed $criteria
     *
     * @return mixed
     */
    public function popCriteria($criteria);

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria();

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria();
}
