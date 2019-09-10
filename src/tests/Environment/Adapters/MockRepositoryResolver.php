<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Adapters;


use LToolkit\Interfaces\IRepositoryResolver;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Test\Environment\Repositories\MockRepository;

class MockRepositoryResolver implements IRepositoryResolver
{
    private $unitOfWork;

    public function __construct(IUnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function getRepository(string $entityClass)
    {
        return new MockRepository($this->unitOfWork);
    }

}
