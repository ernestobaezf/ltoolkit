<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Adapters;


use Psr\Repository\RepositoryInterface;
use Psr\Repository\UnitOfWorkInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;
use LToolkit\Test\Environment\Repositories\MockRepository;

class MockRepositoryResolver implements RepositoryResolverInterface
{
    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function getRepository(string $entityClass): RepositoryInterface
    {
        return new MockRepository($this->unitOfWork);
    }

}
