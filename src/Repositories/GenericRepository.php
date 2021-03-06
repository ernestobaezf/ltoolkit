<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Repositories;


use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Interfaces\GenericRepositoryInterface;

class GenericRepository extends BaseRepository implements GenericRepositoryInterface
{
    private $modelClass;

    public function __construct(UnitOfWorkInterface $unitOfWork, string $modelClass)
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
