<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Connectors;


use LToolkit\Interfaces\IRepositoryFinder;
use LToolkit\Interfaces\IUnitOfWork;
use LToolkit\Test\Environment\Repositories\MockRepository;

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
