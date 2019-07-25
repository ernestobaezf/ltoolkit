<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\Interfaces;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Exception;

/**
 * IHttpClient interface for sending HTTP requests.
 */
interface IHttpClient
{

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options Request options to apply to the given
     *                                  request and to the transfer.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function send(RequestInterface $request, array $options = []);

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method.
     * @param string|UriInterface $uri     URI object or string.
     * @param array               $options Request options to apply.
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function request($method, $uri, array $options = []);

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param string|null $option The config option to retrieve.
     *
     * @return mixed
     */
    public function getConfig($option = null);

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function get($uri, array $options = []): ResponseInterface;

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function head($uri, array $options = []): ResponseInterface;

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function put($uri, array $options = []): ResponseInterface;

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function post($uri, array $options = []): ResponseInterface;

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function patch($uri, array $options = []): ResponseInterface;

    /**
     * Wrapper for the request function passing the method
     *
     * @param  string|UriInterface $uri
     * @param  array               $options
     * @return ResponseInterface
     */
    public function delete($uri, array $options = []): ResponseInterface;
}
