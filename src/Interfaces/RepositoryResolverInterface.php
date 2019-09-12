<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


interface RepositoryResolverInterface
{
    /**
     * Get repository associated to an entity
     *
     * @param  string $entityClass
     * @return BaseRepositoryInterface|RemoteRepositoryInterface
     */
    public function getRepository(string $entityClass);
}
