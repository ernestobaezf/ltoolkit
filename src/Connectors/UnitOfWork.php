<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Connectors;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IBaseRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRemoteRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IRepositoryFinder;

final class UnitOfWork implements IUnitOfWork
{
    /**
     * @var bool
     */
    private $autoCommit = true;

    private $inTransaction = false;

    /**
     * @var IRepositoryFinder
     */
    private $repositoryFinder;

    public function __construct(bool $autoCommit=true)
    {
        $this->setAutoCommit($autoCommit);
        $this->repositoryFinder = App::make(IRepositoryFinder::class, ["unitOfWork" => $this]);
    }

    /**
     * Get a repository corresponding the given entity
     *
     * @param  string $entityClass
     * @return IBaseRepository|IRemoteRepository
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
