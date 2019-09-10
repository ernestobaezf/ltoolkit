<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Adapters;


use LToolkit\Interfaces\IBaseRepository;
use LToolkit\Interfaces\IUnitOfWork;

class MockUnitOfWork implements IUnitOfWork
{

    /**
     * Set behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @param bool $value
     */
    function setAutoCommit(bool $value)
    {
        // TODO: Implement setAutoCommit() method.
    }

    /**
     * Get configured behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @return bool
     */
    function isAutoCommit(): bool
    {
        return true;
    }

    /**
     * Get a repository corresponding the given entity
     *
     * @param string $entityClass
     * @return IBaseRepository
     */
    function getRepository(string $entityClass)
    {
        // TODO: Implement getRepository() method.
    }

    /**
     * Get configured behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if DB::beginTransaction call is made
     *
     */
    function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    /**
     * Wrap to commit the changes to database. This operation requires a previous call to beginTransaction
     *
     */
    function commit()
    {
        // TODO: Implement commit() method.
    }

    /**
     * Wrap to rollback the scheduled changes. This operation requires a previous call to beginTransaction
     *
     */
    function rollback()
    {
        // TODO: Implement rollback() method.
    }
}
