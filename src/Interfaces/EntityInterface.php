<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Interfaces;


use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface EntityInterface extends \ArrayAccess, Arrayable, Jsonable, QueueableEntity, UrlRoutable
{
    /**
     * Get the entity key
     */
    public function getId();

    /**
     * Convert the object to its array representation.
     *
     * return array
     */
    public function toArray();

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0);

    /**
     * @return mixed
     */
    public function getFillable();

    /**
     * Fill the model data from an stdClass
     *
     * @param  \stdClass $std
     * @return $this
     */
    public function fromStdClass(\stdClass $std): EntityInterface;
}
