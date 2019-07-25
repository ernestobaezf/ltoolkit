<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Interfaces;


interface IRepositoryFinder
{
    /**
     * Get repository associated to an entity
     *
     * @param  string $entityClass
     * @return IBaseRepository|IRemoteRepository
     */
    public function getRepository(string $entityClass);
}
