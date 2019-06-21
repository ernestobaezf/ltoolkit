<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Repositories;


use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IGenericRepository;

class GenericRepository extends BaseRepository implements IGenericRepository
{
    private $modelClass;

    public function __construct(IUnitOfWork $unitOfWork, string $modelClass)
    {
        $this->modelClass = $modelClass;
        parent::__construct($unitOfWork);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    protected function model(): string
    {
        return $this->modelClass;
    }
}
