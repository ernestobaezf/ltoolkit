<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Test\Environment\Repositories;


use ltoolkit\Interfaces\IGenericRepository;
use ltoolkit\Interfaces\IUnitOfWork;

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
