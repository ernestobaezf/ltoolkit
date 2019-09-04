<?php
/**
 * @author Ernesto Baez 
 */

namespace ltoolkit\Repositories;


use ltoolkit\Interfaces\IUnitOfWork;
use ltoolkit\Interfaces\IGenericRepository;

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
