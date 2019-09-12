<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Repositories;


use LToolkit\Interfaces\GenericRepositoryInterface;
use LToolkit\Interfaces\UnitOfWorkInterface;

class GenericMockRepository implements GenericRepositoryInterface
{
    private $modelClass;
    private $unitOfWork;

    public function __construct(UnitOfWorkInterface $unitOfWork, string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->unitOfWork = $unitOfWork;
    }

}
