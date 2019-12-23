<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Test\Environment\Models;


use Illuminate\Database\Eloquent\Model;
use Psrx\Repository\EntityInterface;
use LToolkit\Test\Environment\DynamicClass;

class MockEloquentModel extends Model implements EntityInterface
{
    public const RELATIONS = ["relation1:id,name", "relation2.concatenated", "relation3"];

    protected $fillable = [
        "attr1",
        "attr2",
        "attr3",
    ];

    public function load($relations)
    {
        return $this;
    }

    public function relation1()
    {
        $sync = new DynamicClass(["sync" => function($value){}]);

        return $sync;
    }

    /**
     * Get the entity key
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * Fill the model data from an stdClass
     *
     * @param  \stdClass $std
     * @return $this
     */
    public function fromStdClass(\stdClass $std): EntityInterface
    {
        // TODO: Implement fromStdClass() method.
    }
}
