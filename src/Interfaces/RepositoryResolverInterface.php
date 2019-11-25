<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


use Psr\Repository\RepositoryInterface;
use Psr\Repository\UnitOfWorkInterface;

interface RepositoryResolverInterface
{
    public function __construct(UnitOfWorkInterface $unitOfWork);

    /**
     * Get repository associated to an entity
     *
     * @param  string $entityClass
     *
     * @return RepositoryInterface
     */
    public function getRepository(string $entityClass): RepositoryInterface;
}
