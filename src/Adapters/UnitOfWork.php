<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Adapters;

use Illuminate\Support\Facades\DB;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Interfaces\BaseRepositoryInterface;
use LToolkit\Interfaces\RemoteRepositoryInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

final class UnitOfWork implements UnitOfWorkInterface
{
    /**
     * @var bool
     */
    private $autoCommit = true;

    private $inTransaction = false;

    /**
     * @var RepositoryResolverInterface
     */
    private $repositoryFinder;

    /**
     * UnitOfWork constructor.
     * @param bool $autoCommit
     * @throws BindingResolutionException
     */
    public function __construct(bool $autoCommit=true)
    {
        $this->setAutoCommit($autoCommit);
        $this->repositoryFinder = app()->make(RepositoryResolverInterface::class, ["unitOfWork" => $this]);
    }

    /**
     * Get a repository corresponding the given entity
     *
     * @param  string $entityClass
     * @return BaseRepositoryInterface|RemoteRepositoryInterface
     */
    public function getRepository(string $entityClass)
    {
        return $this->repositoryFinder->getRepository($entityClass);
    }

    /**
     * Get configured behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @return bool
     */
    public function isAutoCommit(): bool
    {
        return $this->autoCommit;
    }

    /**
     * Set behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @param bool $value
     */
    public function setAutoCommit(bool $value)
    {
        $this->autoCommit = $value;
    }

    /**
     * Wrap to commit the changes to database. This operation requires a previous call to beginTransaction
     */
    public function beginTransaction()
    {
        if (!$this->isAutoCommit() && !$this->inTransaction) {
            DB::beginTransaction();

            $this->inTransaction = true;
        }
    }

    /**
     * Wrap to commit the changes to database. This operation requires a previous call to beginTransaction
     */
    public function commit()
    {
        if (!$this->isAutoCommit() && $this->inTransaction) {
            DB::commit();

            $this->inTransaction = false;
        }
    }

    /**
     * Wrap to rollback the scheduled changes. This operation requires a previous call to beginTransaction
     */
    public function rollback()
    {
        if (!$this->isAutoCommit() && $this->inTransaction) {
            DB::rollBack();

            $this->inTransaction = false;
        }
    }
}
