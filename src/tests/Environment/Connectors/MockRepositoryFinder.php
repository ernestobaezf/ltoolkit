<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment\Connectors;


use l5toolkit\Interfaces\IRepositoryFinder;
use l5toolkit\Interfaces\IUnitOfWork;
use l5toolkit\Test\Environment\Repositories\MockRepository;

class MockRepositoryFinder implements IRepositoryFinder
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
