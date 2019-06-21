<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;


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
