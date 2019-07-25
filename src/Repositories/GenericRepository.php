<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Repositories;


use l5toolkit\Interfaces\IUnitOfWork;
use l5toolkit\Interfaces\IGenericRepository;

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
