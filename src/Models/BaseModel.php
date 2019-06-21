<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Models;


use Illuminate\Database\Eloquent\Model;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IEntity;

class BaseModel extends Model implements IEntity
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
    public function fromStdClass(\stdClass $std): IEntity
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
