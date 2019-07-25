<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment\Repositories;


use l5toolkit\Interfaces\IGenericRepository;
use l5toolkit\Interfaces\IUnitOfWork;

class GenericMockRepository implements IGenericRepository
{
    private $modelClass;
    private $unitOfWork;

    public function __construct(IUnitOfWork $unitOfWork, string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->unitOfWork = $unitOfWork;
    }

}
