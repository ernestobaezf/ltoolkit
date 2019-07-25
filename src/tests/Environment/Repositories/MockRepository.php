<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment\Repositories;


use Illuminate\Support\Collection;
use l5toolkit\Interfaces\IUnitOfWork;

class MockRepository
{
    private $unitOfWork;

    public function __construct(IUnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
    }

    public function pushCriteria($instance)
    {
        return $this;
    }

    public function all()
    {
        return new Collection(["all"]);
    }

    public function paginate()
    {
        return new Collection(["paginate"]);
    }

    public function find($id){
        if($id==0){
            return null;
        }

        return $id;
    }

    public function create($input){
        return $input;
    }

    public function update($id,$input){
        if($id==0){
            return null;
        }
        return $input;
    }

    public function delete($id){
        if($id==0){
            return null;
        }
        return 1;
    }
}
