<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LToolkit\Interfaces\UnitOfWorkInterface;
use LToolkit\Interfaces\CriteriaIteratorInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;
use LToolkit\Interfaces\APIResourceControllerInterface;

abstract class BaseAPIResourceController extends BaseAPIController implements APIResourceControllerInterface
{
    /**
     * @var CriteriaIteratorInterface
     */
    private $criteria;

    /**
     * BaseAPIResourceController constructor.
     *
     * @param UnitOfWorkInterface        $unitOfWork
     * @param ValidatorResolverInterface $validatorResolver
     * @param CriteriaIteratorInterface  $criteria          Used to filter content in index method
     */
    public function __construct(UnitOfWorkInterface $unitOfWork,
                                ValidatorResolverInterface $validatorResolver,
                                CriteriaIteratorInterface $criteria
    ) {
        $this->criteria = $criteria;

        parent::__construct($unitOfWork, $validatorResolver);
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
                $repository = $repository->pushCriteria($instance);
            }
        }

        if ($limit = $request->get('limit')) {
            $entities = $repository->paginate($limit);
        } else {
            $entities = $repository->all();
        }

        return $this->respond($entities, trans_choice('ltoolkit::messages.entity.retrieved', $entities->count()));
    }

    /**
     * @inheritdoc
     */
    public function show(int $id): JsonResponse
    {
        $request = request();
        $with = $request->query->get("with", "");

        $relations = [];
        if ($with) {
            $relations = explode(';', $with);
        }

        return $this->showWithRelationList($id, $relations);
    }

    /**
     * Get the entity detail with relations is requested
     *
     * @param int   $id
     * @param array $relations
     *
     * @return JsonResponse
     */
    protected function showWithRelationList(int $id, array $relations): JsonResponse
    {
        $repository = $this->getRepository();
        $entity = $repository->find($id, ['*', 'relations' => $relations]);

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

        if (!$entity) {
            return $this->respond(null, trans('ltoolkit::messages.entity.not_found'), 404);
        }

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
}
