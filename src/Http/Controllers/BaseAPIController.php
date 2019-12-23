<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Http\Controllers;


use Closure;
use Illuminate\Http\JsonResponse;
use Psrx\Repository\EntityInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Psrx\Repository\RepositoryInterface;
use Psrx\Repository\UnitOfWorkInterface;
use Illuminate\Support\Facades\Response;
use LToolkit\Interfaces\SerializerInterface;
use LToolkit\Interfaces\ValidatorResolverInterface;
use LToolkit\Interfaces\RepositoryResolverInterface;

abstract class BaseAPIController extends Controller
{
    /**
     * @var UnitOfWorkInterface $unitOfWork
     */
    private $repositoryResolver;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * BaseAPIController constructor.
     *
     * @param RepositoryResolverInterface $repositoryResolver
     * @param ValidatorResolverInterface  $validatorResolver
     */
    public function __construct(RepositoryResolverInterface $repositoryResolver,
                                ValidatorResolverInterface $validatorResolver)
    {
        $this->repositoryResolver = $repositoryResolver;

        $this->middleware($this->validationsClause($validatorResolver));
    }

    /**
     * Set the serializer used to modify the data in the response
     *
     * @param SerializerInterface $serializer
     */
    public final function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get the serializer used to modify the data in the response
     *
     * @return null|SerializerInterface
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
     * @return RepositoryInterface
     */
    protected function getRepository(string $entityClass=null): RepositoryInterface
    {
        $entityClass = $entityClass ?: $this->getEntity();
        return $this->getRepositoryResolver()->getRepository($entityClass);
    }

    /**
     * Get Unit of work to control operations that alter database
     *
     * @return RepositoryResolverInterface
     */
    protected final function getRepositoryResolver(): RepositoryResolverInterface
    {
        return $this->repositoryResolver;
    }

    /**
     * Format the response in a standardized json. If the message param is passed then the body has the following
     * structure: ['data' => data, 'message' => message].
     *
     * @param  EntityInterface|int|mixed $data
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
        } elseif ($data instanceof EntityInterface) {
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
     * @param ValidatorResolverInterface $validatorResolver
     * @return Closure
     */
    protected function validationsClause(ValidatorResolverInterface $validatorResolver): Closure
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
