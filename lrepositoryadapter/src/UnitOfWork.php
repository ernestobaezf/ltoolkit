<?php
/**
 * @author Ernesto Baez 
 */

namespace LRepositoryAdapter;

use Illuminate\Support\Facades\DB;
use Psr\Repository\UnitOfWorkInterface;

final class UnitOfWork implements UnitOfWorkInterface
{
    /**
     * @var bool
     */
    private $autoCommit = true;

    private $inTransaction = false;

    /**
     * UnitOfWork constructor.
     *
     * @param bool $autoCommit
     */
    public function __construct(bool $autoCommit=true)
    {
        $this->setAutoCommit($autoCommit);
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