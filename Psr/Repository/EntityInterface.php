<?php
/**
 * @author Ernesto Baez 
 */

namespace Psr\Repository;


use stdClass;

/**
 * Interface EntityInterface
 *
 * @package Psr\Repository
 */
interface EntityInterface
{
    /**
     * Get the entity key
     *
     * @return string|int
     */
    function getId();

    /**
     * Convert the object to its array representation.
     *
     * return array
     */
    function toArray();

    /**
     * @return array
     */
    function getFillableFields(): array;

    /**
     * Fill the model data from an stdClass
     *
     * @param  stdClass $std
     * @return $this
     */
    function fromStdClass(stdClass $std): EntityInterface;
}
