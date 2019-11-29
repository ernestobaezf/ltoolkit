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
     * Get the current Unit of Work used
     *
     * @return UnitOfWorkInterface
     */
    function unitOfWork(): UnitOfWorkInterface;
}
