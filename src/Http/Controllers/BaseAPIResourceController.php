<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LToolkit\Interfaces\CriteriaResolverInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;
use LToolkit\Interfaces\APIResourceControllerInterface;

abstract class BaseAPIResourceController extends BaseAPIController implements APIResourceControllerInterface
{
    /**
     * @var CriteriaResolverInterface
     */
    private $criteria;

    /**
     * BaseAPIResourceController constructor.
     *
     * @param ValidatorResolverInterface $validatorResolver
     * @param CriteriaResolverInterface $criteria Used to filter content in index method
     * @param RepositoryResolverInterface $repositoryResolver
     */
    public function __construct(ValidatorResolverInterface $validatorResolver,
                                CriteriaResolverInterface $criteria,
                                RepositoryResolverInterface $repositoryResolver
    ) {
        $this->criteria = $criteria;

        parent::__construct($repositoryResolver, $validatorResolver);
    }

    /**
     * Examples using criteria
     * /api/v1/sample?search=element1&searchFields=name
     * /api/v1/sample?search=element1&searchFields=name:like&page=1&limit=1
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $repository = $this->getRepository();

        if ($request->all()) {
            foreach ($this->criteria as $instance) {
                $repository = $repository->setCriteria($instance);
            }
        }

        $columns = $this->extractColumns($request->get("columns", "") ?? "");

        if ($limit = $request->get('limit')) {
            $entities = $repository->paginate($limit, $columns);
        } else {
            $entities = $repository->all($columns);
        }

        return $this->respond($entities, trans_choice('ltoolkit::messages.entity.retrieved', $entities->count()));
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function show(int $id): JsonResponse
    {
        $request = request();
        $with = $request->query->get("with", "");

        $relations = [];
        if ($with) {
            $relations = explode(';', $with);
        }

        $columns = $this->extractColumns($request->get("columns", "") ?? "");

        if ($relations) {
            $columns += ['relations' => $relations];
        }

        return $this->showWithRelationList($id, $columns);
    }

    /**
     * Get the entity detail with relations is requested
     *
     * @param int $id
     * @param array $columns
     *
     * @return JsonResponse
     * @throws Exception
     */
    protected function showWithRelationList(int $id, array $columns=["*"]): JsonResponse
    {
        $repository = $this->getRepository();

        $entity = $repository->find($id, $columns);
        return $this->respond($entity, trans_choice('ltoolkit::messages.entity.retrieved', 1));
    }

    /**
     * @inheritdoc
     *
     * @throws Exception Handle this exception in the laravel exception Handler
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $repository = $this->getRepository();
        $entity = $repository->create($input);

        return $this->respond($entity, trans('ltoolkit::messages.entity.saved'));
    }

    /**
     * @inheritdoc
     *
     * @throws Exception Handle this exception in the laravel exception Handler
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $input = $request->all();

        $repository = $this->getRepository();
        $entity = $repository->update($id, $input);

        return $this->respond($entity, trans('ltoolkit::messages.entity.updated'));
    }

    /**
     * @inheritdoc
     *
     * @throws Exception Handle this exception in the laravel exception Handler
     */
    public function destroy(int $id): JsonResponse
    {
        $this->getRepository()->delete($id);

        return $this->respond($id, trans('ltoolkit::messages.entity.deleted'));
    }

    /**
     * Get columns from request
     *
     * @param string $columns
     *
     * @return array
     */
    private function extractColumns(string $columns): array
    {
        $_columns = array_filter(explode(";", $columns));
        $columns = ["*"];
        if ($_columns) {
            $columns = $_columns;
        }
        return $columns;
    }
}
