<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\Http\Controllers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\ICriteriaIterator;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IValidatorResolver;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IAPIResourceController;

abstract class BaseAPIResourceController extends BaseAPIController implements IAPIResourceController
{
    /**
     * @var ICriteriaIterator
     */
    private $criteria;

    /**
     * BaseAPIResourceController constructor.
     *
     * @param IUnitOfWork        $unitOfWork
     * @param IValidatorResolver $validatorResolver
     * @param ICriteriaIterator  $criteria          Used to filter content in index method
     */
    public function __construct(IUnitOfWork $unitOfWork,
        IValidatorResolver $validatorResolver,
        ICriteriaIterator $criteria
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

        return $this->respond($entities, trans_choice('L5CoreToolbox::messages.entity.retrieved', $entities->count()));
    }

    /**
     * @inheritdoc
     */
    public function show(int $id): JsonResponse
    {
        $repository = $this->getRepository();
        $entity = $repository->find($id);

        if (!$entity) {
            return $this->respond(null, trans('L5CoreToolbox::messages.entity.not_found'), 404);
        }

        return $this->respond($entity, trans_choice('L5CoreToolbox::messages.entity.retrieved', 1));
    }

    /**
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

        if (!$entity) {
            return $this->respond(null, trans('L5CoreToolbox::messages.entity.not_found'), 404);
        }

        return $this->respond($entity, trans_choice('L5CoreToolbox::messages.entity.retrieved', 1));
    }

    /**
     * @inheritdoc
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $repository = $this->getRepository();
            $entity = $repository->create($input);

            return $this->respond($entity, trans('L5CoreToolbox::messages.entity.saved'));
        } catch (Exception $exception) {
            report($exception);

            return $this->respond(null, $exception->getMessage(), 400);
        }
    }

    /**
     * @inheritdoc
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $input = $request->all();

            $repository = $this->getRepository();
            $entity = $repository->update($id, $input);

            if (!$entity) {
                return $this->respond(null, trans('L5CoreToolbox::messages.entity.not_found'), 404);
            }

            return $this->respond($entity, trans('L5CoreToolbox::messages.entity.updated'));
        } catch (Exception $exception) {
            report($exception);

            return $this->respond(null, $exception->getMessage(), 400);
        }
    }

    /**
     * @inheritdoc
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->getRepository()->delete($id);

        if (!$deleted) {
            return $this->respond(null, trans('L5CoreToolbox::messages.entity.not_found'), 404);
        }

        return $this->respond($id, trans('L5CoreToolbox::messages.entity.deleted'));
    }
}
