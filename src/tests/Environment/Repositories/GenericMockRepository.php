<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories;


use ErnestoBaezF\L5CoreToolbox\Interfaces\IGenericRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;

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