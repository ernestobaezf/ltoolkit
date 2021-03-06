<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Models;


use Illuminate\Database\Eloquent\Model;
use LToolkit\Interfaces\EntityInterface;

class BaseModel extends Model implements EntityInterface
{
    public const RELATIONS = [];

    /**
     * Get model id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set model id
     *
     * @param  mixed $id
     * @return $this
     */
    protected final function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Fill the model data from an stdClass
     *
     * @param  \stdClass $std
     * @return $this
     */
    public function fromStdClass(\stdClass $std): EntityInterface
    {
        // backup fillable
        $fillable = $this->getFillable();

        $exists = $std->id ?? 0;
        if ($exists) {
            // set id and other fields you want to be filled
            $this->fillable($fillable + ['id']);
        }

        // fill $this->attributes array
        $this->fill((array) $std);

        // fill $this->original array
        $this->syncOriginal();

        $this->exists = $exists;

        // restore fillable
        $this->fillable($fillable);

        return $this;
    }
}
