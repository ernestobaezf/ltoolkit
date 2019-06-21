<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Connectors;


use ErnestoBaezF\L5CoreToolbox\Interfaces\IRepositoryFinder;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories\MockRepository;

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