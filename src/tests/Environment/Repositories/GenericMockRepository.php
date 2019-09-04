<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Repositories;


use LToolkit\Interfaces\IGenericRepository;
use LToolkit\Interfaces\IUnitOfWork;

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
