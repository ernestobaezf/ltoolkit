<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Repositories;


use Psr\Repository\UnitOfWorkInterface;
use LToolkit\Interfaces\GenericRepositoryInterface;

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
