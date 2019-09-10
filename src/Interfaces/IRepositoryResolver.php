<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


interface IRepositoryResolver
{
    /**
     * Get repository associated to an entity
     *
     * @param  string $entityClass
     * @return IBaseRepository|IRemoteRepository
     */
    public function getRepository(string $entityClass);
}
