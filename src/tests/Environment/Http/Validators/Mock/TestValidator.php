<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Test\Environment\Http\Validators\Mock;


use l5toolkit\Interfaces\IValidator;

class TestValidator implements IValidator
{

    public function setData(array $data)
    {
        // TODO: Implement setData() method.
    }

    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag()
    {
        // TODO: Implement getMessageBag() method.
    }

    /**
     * Run the validator's rules against its data.
     *
     * @return array
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }

    /**
     * Get the attributes and values that were validated.
     *
     * @return array
     */
    public function validated()
    {
        // TODO: Implement validated() method.
    }

    /**
     * Determine if the data fails the validation rules.
     *
     * @return bool
     */
    public function fails()
    {
        // TODO: Implement fails() method.
    }

    /**
     * Get the failed validation rules.
     *
     * @return array
     */
    public function failed()
    {
        // TODO: Implement failed() method.
    }

    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param  string|array $attribute
     * @param  string|array $rules
     * @param  callable $callback
     * @return $this
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        // TODO: Implement sometimes() method.
    }

    /**
     * Add an after validation callback.
     *
     * @param  callable|string $callback
     * @return $this
     */
    public function after($callback)
    {
        // TODO: Implement after() method.
    }

    /**
     * Get all of the validation error messages.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        // TODO: Implement errors() method.
    }
}
