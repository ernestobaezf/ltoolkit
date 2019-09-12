<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Adapters;


use LToolkit\Interfaces\RepositoryResolverInterface;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Test\Environment\Repositories\MockRepository;

class MockRepositoryResolver implements RepositoryResolverInterface
{
    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function getRepository(string $entityClass)
    {
        return new MockRepository($this->unitOfWork);
    }

}
