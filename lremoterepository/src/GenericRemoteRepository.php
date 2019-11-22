<?php
/**
 * @author Ernesto Baez
 */

namespace LToolkit\Repositories;


use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use \Illuminate\Http\Request as IlluminateRequest;

abstract class GenericRemoteRepository extends RemoteRepository
{
    protected $headers = [
        "x-correlation-id" => "",
        "x-request-id" => "",
    ];

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     *
     * @throws Exception
     * @throws ClientExceptionInterface
     */
    public function all($columns = ['*'])
    {
        $client = $this->getHttpClient();

        $request = new Request(IlluminateRequest::METHOD_GET,
            $this->getUrl("limits/volume/mid").http_build_query($this->getCriteria()),
            $this->getHeaders());
        $response = $client->sendRequest($request);

        return collect((array)json_decode($response->getBody()->getContents()));
    }

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        // TODO: Implement find() method.
    }

    /**
     * Find data by field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*'])
    {
        // TODO: Implement findByField() method.
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        // TODO: Implement create() method.
    }

    /**
     * Update a entity in repository by id
     *
     * @param int $id
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        // TODO: Implement update() method.
    }

    /**
     * Delete a entity in repository by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
