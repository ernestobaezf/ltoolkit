<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\test\Unit\Connectors;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use ErnestoBaezF\L5CoreToolbox\Connectors\HttpClientConnector;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Connectors\MockRequest;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\Connectors\MockResponse;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class HttpClientConnectorTest extends TestCase
{
    private function createClient($method)
    {
        $this->app->extend(ClientInterface::class, function() use ($method) {
            $object = $this->getMockBuilder(Client::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->setMethods([$method])
                ->getMock();

            $object->method($method)->willReturn(new MockResponse());
            return $object;
        });

        $client = $this->getMockBuilder(HttpClientConnector::class)
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setConstructorArgs([[]])
            ->getMock();

        return $client;
    }

    public function test_send()
    {
        $client = $this->createClient("send");

        $method = self::getMethod("send", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_request()
    {
        $client = $this->createClient("request");

        $method = self::getMethod("request", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest(), ""]);

        self::assertTrue(true);
    }

    public function test_getConfig()
    {
        $client = $this->createClient("getConfig");

        $method = self::getMethod("getConfig", HttpClientConnector::class);
        $method->invoke($client);

        self::assertTrue(true);
    }

    public function test_get()
    {
        $client = $this->createClient("get");

        $method = self::getMethod("get", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_head()
    {
        $client = $this->createClient("head");

        $method = self::getMethod("head", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_put()
    {
        $client = $this->createClient("put");

        $method = self::getMethod("put", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_post()
    {
        $client = $this->createClient("post");

        $method = self::getMethod("post", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_patch()
    {
        $client = $this->createClient("patch");

        $method = self::getMethod("patch", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }

    public function test_delete()
    {
        $client = $this->createClient("delete");

        $method = self::getMethod("delete", HttpClientConnector::class);
        $method->invokeArgs($client, [new MockRequest()]);

        self::assertTrue(true);
    }
}
