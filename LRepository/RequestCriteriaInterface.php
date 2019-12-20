<?php
/**
 * @author Ernesto Baez
 */

namespace  LRepositoryAdapter\Interfaces;


use Psr\Repository\CriteriaInterface;

/**
 * Interface RequestCriteriaInterface
 *
 * @package LRepositoryAdapter\Interfaces
 */
interface RequestCriteriaInterface extends CriteriaInterface
{
    /**
     * Get element to search
     *
     * @return string|string[] Example: "john" | [<field> => <value>, "name" => "john"]
     */
    function getSearch();

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
     * Get the sort to apply to the results (asc or desc)
     *
     * @return string|null <"asc|desc">
     */
    public function getSortBy(): ?string;

    /**
     * Get list of related entities to attach to the result
     *
     * @return string[]
     */
    function getRelations(): array;

    /**
     * Get the logical operation used to filter
     *
     * @return null|string <"and"|"or">
     */
    function getOperation(): ?string;
}
