<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


use Psr\Repository\RepositoryInterface;

interface RepositoryResolverInterface
{
    /**
     * Get repository associated to an entity
     *
     * @param  string $entityClass
     * @return RepositoryInterface
     */
    public function getRepository(string $entityClass);
}
