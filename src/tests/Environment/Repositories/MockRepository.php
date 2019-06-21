<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Test\Environment\Repositories;


use Illuminate\Support\Collection;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;

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
