<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Test\Environment\Connectors;


use ltoolkit\Interfaces\IRepositoryFinder;
use ltoolkit\Interfaces\IUnitOfWork;
use ltoolkit\Test\Environment\Repositories\MockRepository;

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
