<?php
/**
 * User: Ernesto Baez <ernesto.baez@cdev.global>
 * Date: 02/12/19 11:04 AM
 */

namespace LRepositoryAdapter;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Psr\Repository\CriteriaInterface;
use LRepositoryAdapter\Interfaces\CriteriaAdapterInterface;
use LRepositoryAdapter\Interfaces\RequestCriteriaInterface;
use Prettus\Repository\Contracts\CriteriaInterface as PrettusCriteriaInterface;

class RequestCriteria implements RequestCriteriaInterface, CriteriaAdapterInterface
{
    /**
     * @var Request|Collection
     */
    private $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * Get element to search
     *
     * @return null|string|string[] Example: "john" | [<field> => <value>, "name" => "john"]
     */
    public function getSearch()
    {
        $search = $this->request->get(config('repository.criteria.params.search', 'search'), []);

        if (!$search || is_string($search)) {
            return $search;
        }

        //If the search value in the request is in the form of search=name:John;email:john
        return $this->parseMultiValueField($search);
    }

    /**
     * Get fields to apply the search and the comparison operation
     *
     * @return string[] Example: [<field1> => <op (like|=|!=)>, "name" => "like"]
     */
    public function getSearchFields(): array
    {
        $search = $this->request->get(config('repository.criteria.params.searchFields', 'searchFields'), []);

        //If the search value in the request is in the form of search=name:like;email:=
        return $this->parseMultiValueField($search);
    }

    /**
     * Get the fields with data from the source (columns in case of tables)
     *
     * @return string[]
     */
    public function getFields(): array
    {
        $fields = $this->request->get(config('repository.criteria.params.filter', 'filter'));

        return explode(";", $fields);
    }

    /**
     * Get the order to apply to the results
     *
     * @return string[]|null <fields[]>
     */
    public function getOrderBy(): ?array
    {
        return explode(";", $this->request->get(config('repository.criteria.params.orderBy', 'orderBy'), ""));
    }

    /**
     * Get the sort to apply to the results (asc or desc)
     *
     * @return string|null <"asc|desc">
     */
    public function getSortBy(): ?string
    {
        return $this->request->get(config('repository.criteria.params.sortedBy', 'sortedBy'));
    }

    /**
     * Get list of related entities to attach to the result
     *
     * @return string[]
     */
    public function getRelations(): array
    {
        return $this->request->get(config('repository.criteria.params.with', 'with'), []);
    }

    /**
     * Get the logical operation used to filter
     *
     * @return string|null <and|or>
     */
    public function getOperation(): ?string
    {
        return $this->request->get(config('repository.criteria.params.searchJoin', 'searchJoin'), []);
    }

    /**
     * @param $search
     * @return array
     */
    private function parseMultiValueField($search): array
    {
        $parts = explode(";", $search);

        $result = [];
        foreach ($parts as $part) {
            $unBox = explode(":", $part);
            $result[$unBox[0]] = $unBox[1];
        }
        return $result;
    }

    /**
     * Apply criteria
     *
     * @param mixed $model
     *
     * @return CriteriaInterface
     */
    function apply($model): CriteriaInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function cast(): PrettusCriteriaInterface
    {
        $result = new \Prettus\Repository\Criteria\RequestCriteria($this->request);
        return $result;
    }
}
