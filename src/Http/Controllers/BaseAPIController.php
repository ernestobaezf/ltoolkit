<?php
/**
 * @author Ernesto Baez
 */

namespace ErnestoBaezF\L5CoreToolbox\Http\Controllers;


use Closure;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Response;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IEntity;
use ErnestoBaezF\L5CoreToolbox\Interfaces\ISerializer;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IUnitOfWork;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IBaseRepository;
use ErnestoBaezF\L5CoreToolbox\Interfaces\IValidatorResolver;

abstract class BaseAPIController extends Controller
{
    /**
     * @var IUnitOfWork $unitOfWork
     */
    private $unitOfWork;

    /**
     * @var ISerializer
     */
    private $serializer;

    /**
     * BaseAPIController constructor.
     *
     * @param IUnitOfWork        $unitOfWork
     * @param IValidatorResolver $validatorResolver
     */
    public function __construct(IUnitOfWork $unitOfWork, IValidatorResolver $validatorResolver)
    {
        $this->unitOfWork = $unitOfWork;

        $this->middleware($this->validationsClause($validatorResolver));
    }

    /**
     * Set the serializer used to modify the data in the response
     *
     * @param ISerializer $serializer
     */
    public final function setSerializer(ISerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get the serializer used to modify the data in the response
     *
     * @return null|ISerializer
     */
    protected final function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Get the main entity used in the current controller to get the associated repository
     *
     * @return string
     */
    protected abstract function getEntity(): string;

    /**
     * @param string $entityClass
     *
     * @return IBaseRepository
     */
    protected function getRepository(string $entityClass=null)
    {
        $entityClass = $entityClass ?: $this->getEntity();
        return $this->getUnitOfWork()->getRepository($entityClass);
    }

    /**
     * Get Unit of work to control operations that alter database
     *
     * @return IUnitOfWork
     */
    protected function getUnitOfWork(): IUnitOfWork
    {
        return $this->unitOfWork;
    }

    /**
     * Format the response in a standardized json. If the message param is passed then the body has the following
     * structure: ['data' => data, 'message' => message].
     *
     * @param  IEntity|int|mixed $data
     * @param  string|null       $message
     * @param  int               $status
     * @param  array             $headers
     * @param  int               $options
     *
     * @return JsonResponse
     */
    protected function respond($data, $message=null, int $status=200, array $headers=[], int $options=0): JsonResponse
    {
        $serializer = $this->getSerializer();
        if ($serializer) {
            $data = $serializer->serialize($data);
        } elseif ($data instanceof IEntity) {
            $data = $data->toArray();
        }

        if (!$message) {
            return Response::json($data, $status, $headers, $options);
        }

        return Response::json(['data' => $data, 'message' => $message], $status, $headers, $options);
    }

    /**
     * Internal function to get validations
     *
     * @param IValidatorResolver $validatorResolver
     * @return Closure
     */
    protected function validationsClause(IValidatorResolver $validatorResolver): Closure
    {
        return function ($request, $next) use ($validatorResolver) {
            return evaluator()->preCondition(
                function () use ($validatorResolver) {
                    $currentRoute = Route::current();
                    $path = $currentRoute ? $currentRoute->getActionName() : "";
                    list($_, $actionName) = explode("@", $path ?: "@");

                    $validator = $validatorResolver->get($actionName);

                    if ($validator) {
                        $validator->validate();
                    }
                }
            )->mainMethod(
                function () use ($request, $next) {
                    return $next($request);
                }
            )->evaluate();
        };
    }
}
