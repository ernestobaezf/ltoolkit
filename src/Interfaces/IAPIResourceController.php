<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\Interfaces;


use Illuminate\Http\Request;


interface IAPIResourceController
{
    /**
     * Get the list of entities
     *
     * Examples using criteria
     * /api/v1/sample?search=element1&searchFields=name
     * /api/v1/sample?search=element1&searchFields=name:like&page=1&limit=1
     *
     * @param Request $request
     */
    function index(Request $request);

    /**
     * Get the entity detail
     *
     * @param int $id
     */
    function show(int $id);

    /**
     * Create a new entity
     *
     * @param Request $request
     */
    function store(Request $request);

    /**
     * Update an entity
     *
     * @param int     $id
     * @param Request $request
     */
    function update(int $id, Request $request);

    /**
     * Deletes an entity
     *
     * @param  int $id
     * @return mixed
     */
    function destroy(int $id);
}
