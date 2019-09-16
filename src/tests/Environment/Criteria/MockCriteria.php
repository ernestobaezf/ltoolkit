<?php


namespace LToolkit\Test\Environment\Criteria;


use Iterator;
use LToolkit\Interfaces\CriteriaResolverInterface;


class MockCriteria implements CriteriaResolverInterface
{

    /**
     * Assign the criteria set associated to a repository
     *
     * @param string $key repository interface or class name
     * @param array $criteria set of criteria instances to be applied to the repository
     *
     * @return void
     */
    public function set(string $key, array $criteria)
    {
        // TODO: Implement set() method.
    }

    /**
     * Return the iterator of criteria associated to the key
     *
     * @param string $key repository interface or class name
     *
     * @return Iterator
     */
    public function get(string $key): Iterator
    {
        // TODO: Implement get() method.
    }

    /**
     * Returns true if there is a set of criteria for the given key
     *
     * @param string $key repository interface or class name
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        // TODO: Implement has() method.
    }
}
