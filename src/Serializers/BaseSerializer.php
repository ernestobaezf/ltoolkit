<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Serializers;


use Illuminate\Support\Collection;
use LToolkit\Interfaces\ISerializer;

abstract class BaseSerializer implements ISerializer
{

    /**
     * Transform a collection of items.
     *
     * @param  Collection $data
     * @return array
     */
    protected function collection(Collection $data)
    {
        return $data->map(function($item) {
            return $this->transform($item);
        })->toArray();
    }

    /**
     * Transform a single item.
     *
     * @param  mixed $data
     * @return mixed
     */
    protected function item($data)
    {
        $result = $this->transform($data);
        return $result;
    }

    /**
     * Apply the transformation.
     *
     * @param  mixed $data
     * @return mixed
     */
    public function serialize($data)
    {
        if (is_array($data)) {
            $data = new Collection($data);
        }

        if ($data instanceof Collection) {
            return $this->collection($data);
        }

        return $this->transform($data);
    }

    /**
     * Transform the data during the serialization process
     *
     * @param  mixed $data
     * @return mixed
     */
    protected abstract function transform($data);
}
