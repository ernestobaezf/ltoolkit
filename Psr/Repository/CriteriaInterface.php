<?php
/**
 * @author Ernesto Baez
 */

namespace Psr\Repository;


/**
 * Interface CriteriaInterface
 *
 * @package Psr\Repository
 */
interface CriteriaInterface
{
    /**
     * Apply criteria
     *
     * @param mixed               $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    function apply($model, RepositoryInterface $repository);

    /**
     * Get element to search
     *
     * @return string|string[] Example: "john" | [<field> => <value>, "name" => "john"]
     */
    function getSearch(): array ;

    /**
     * Get fields to apply the search to and the comparison operation
     *
     * @return string[] Example: [<field1> => <op (like|=|!=)>, "name" => "like"]
     */
    function getSearchFields(): array;

    /**
     * Get the fields with data from the source (columns in case of tables)
     *
     * @return string[]
     */
    function getFields(): array;

    /**
     * Get the order to apply to the results
     *
     * @return array|null [<fields[]>, "asc|desc"]
     */
    function getOrderBy(): ?array;

    /**
     * Get list of related entities to attach to the result
     *
     * @return string[]
     */
    function getRelations(): array;

    /**
     * Get the logical operation used to filter
     *
     * @return string <and|or>
     */
    function getOperations();
}
