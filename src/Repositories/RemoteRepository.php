<?php
/**
 * @author Ernesto Baez 
 */

namespace LToolkit\Repositories;


use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Psr\Http\Client\ClientInterface;
use LToolkit\Interfaces\IRemoteRepository;

abstract class RemoteRepository implements IRemoteRepository
{
    /**
     * @var Collection $criteria
     */
    private $criteria;

    private $client = null;

    public function __construct()
    {
        $this->criteria = collect();
    }

    /**
     * Return http client to call remote services
     *
     * @param  array $options
     *
     * @return ClientInterface
     */
    protected function getHttpClient(array $options=[]): ClientInterface
    {
        if (!$this->client) {
            return $this->client = app(ClientInterface::class, ["options" => $options]);
        }

        return $this->client;
    }

    /**
     * Push Criteria for filter the query
     *
     * @param mixed $criteria
     *
     * @return $this
     */
    public function pushCriteria($criteria)
    {
        $this->criteria = $this->criteria->merge($criteria);

        return $this;
    }

    /**
     * Pop Criteria
     *
     * @param mixed $criteria
     *
     * @return mixed
     */
    public function popCriteria($criteria)
    {
        return $this->criteria->pull($criteria);
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        return $this;
    }

    /**
     * Reset all Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = collect();

        return $this;
    }

    /**
     * Get the partial uri to the particular resource. Example: /reservations/
     *
     * @return string
     */
    protected abstract function resourceUri();

    /**
     * Get the base url to the particular resource. Example: http://resource-api.com/
     *
     * @return string
     */
    protected abstract function baseUrl();

    /**
     * Get base Url to call the service
     *
     * @param string $uri
     * @param string|int $apiVersion
     *
     * @return string
     *
     * @throws Exception
     */
    protected final function getUrl(string $uri = '', $apiVersion = "1"): string
    {
        $base = $this->baseUrl();
        $resourceUri = $this->resourceUri();

        $glue = "";
        if ($uri && !Str::startsWith($uri, "/") && $resourceUri && !Str::endsWith($resourceUri, "/")) {
            $glue = "/";
        }

        if (Str::startsWith($uri, "/") && Str::endsWith($resourceUri, "/")) {
            $uri = Str::replaceFirst("/", "", $uri);
        }

        $serviceURI = $resourceUri.$glue.$uri;

        if (!$base) {
            throw new Exception("Missing base url definition");
        }

        if (!Str::endsWith($base, "/")) {
            $base = $base."/";
        }

        if (Str::startsWith($serviceURI, "/")) {
            $serviceURI = Str::replaceFirst("/", "", $serviceURI);
        }

        return $base."api/v$apiVersion/$serviceURI";
    }
}
