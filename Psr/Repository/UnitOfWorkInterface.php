<?php
/**
 * @author Ernesto Baez 
 */

namespace Psr\Repository;

/**
 * Interface UnitOfWorkInterface
 *
 * @package Psr\Repository
 */
interface UnitOfWorkInterface
{
    /**
     * Set behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @param bool $value
     */
    function setAutoCommit(bool $value);

    /**
     * Get configured behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if beginTransaction call is made.
     *
     * @return bool
     */
    function isAutoCommit(): bool;

    /**
     * Get configured behaviour to determine if the actions to modify the database
     * should automatically commit the changes. This should determine if DB::beginTransaction call is made
     */
    function beginTransaction();

    /**
     * Wrap to commit the changes to database. This operation requires a previous call to beginTransaction
     */
    function commit();

    /**
     * Wrap to rollback the scheduled changes. This operation requires a previous call to beginTransaction
     */
    function rollback();
}
