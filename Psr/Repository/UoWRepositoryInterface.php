<?php
namespace Psr\Repository;


use Exception;
use Illuminate\Support\Collection;

/**
 * Unit of Work repository interface.
 *
 * Interface UoWRepositoryInterface
 *
 * @package Psr\Repository
 */
interface UoWRepositoryInterface extends RepositoryInterface
{
    /**
     * Set whether the repository is transaction or not
     *
     * @return UnitOfWorkInterface
     */
    public function unitOfWork();
}
