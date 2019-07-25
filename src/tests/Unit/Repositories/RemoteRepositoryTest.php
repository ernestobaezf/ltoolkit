<?php
/**
 * @author Ernesto Baez 
 */

namespace l5toolkit\tests\Unit\Repositories;


use Exception;
use l5toolkit\Interfaces\IHttpClient;
use l5toolkit\Repositories\RemoteRepository;
use l5toolkit\Test\Environment\Connectors\MockUnitOfWork;
use l5toolkit\Test\Environment\TestCase;

class RemoteRepositoryTest extends TestCase
{
    /**
     * Get client by first time and once assigned
     */
    public function test_getHttpClient()
    {
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->disableOriginalClone()
            ->disableOriginalConstructor()
            ->getMock();

        $method = self::getMethod("getHttpClient", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertInstanceOf(IHttpClient::class, $result);

        $method = self::getMethod("getHttpClient", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertInstanceOf(IHttpClient::class, $result);
    }

    /**
     * Push criteria twice and pop once
     */
    public function test_pushCriteria_popCriteria_resetCriteria()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->getMock();

        $method = self::getMethod("pushCriteria", RemoteRepository::class);
        $method->invokeArgs($object, [["key1" => "value1"]]);
        $result = $method->invokeArgs($object, [["key2" => "value2"]]);

        self::assertInstanceOf(RemoteRepository::class, $result);

        $method = self::getMethod("getCriteria", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertEquals(collect(["key1" => "value1", "key2" => "value2"]), $result);

        $method = self::getMethod("popCriteria", RemoteRepository::class);
        $result = $method->invokeArgs($object, ["key2"]);

        self::assertEquals("value2", $result);

        $method = self::getMethod("getCriteria", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertEquals(collect(["key1" => "value1"]), $result);

        $method = self::getMethod("resetCriteria", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertInstanceOf(RemoteRepository::class, $result);

        $method = self::getMethod("getCriteria", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertEquals(collect(), $result);
    }

    /**
     * Skip criteria
     */
    public function test_skipCriteria()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->getMock();

        $method = self::getMethod("skipCriteria", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertInstanceOf(RemoteRepository::class, $result);
    }

    /**
     * Get base Url to call the service
     * Context: Base url not defined
     */
    public function test_getUrl_1()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->setMethods(["baseUrl", "resourceUri", "all","find","findByField","create","update","delete"])
            ->getMock();

        $object->method("all")->willReturn("");
        $object->method("find")->willReturn("");
        $object->method("findByField")->willReturn("");
        $object->method("create")->willReturn("");

        $object->method("baseUrl")->willReturn("");
        $object->method("resourceUri")->willReturn("");

        $this->expectException(Exception::class);
        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertInstanceOf(RemoteRepository::class, $result);
    }

    /**
     * Get base Url to call the service
     * Context: Base url defined and not defined resourceUri
     */
    public function test_getUrl_2()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->setMethods(["baseUrl", "resourceUri", "all","find","findByField","create","update","delete"])
            ->getMock();

        $object->method("all")->willReturn("");
        $object->method("find")->willReturn("");
        $object->method("findByField")->willReturn("");
        $object->method("create")->willReturn("");

        $url = "http://remo-service.com";
        $resource = "resource";
        $object->method("baseUrl")->willReturn($url);
        $object->method("resourceUri")->willReturn("");

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invoke($object);

        self::assertEquals("$url/api/v1/", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, [$resource, 2]);

        self::assertEquals("$url/api/v2/$resource", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, ["/$resource", 2]);

        self::assertEquals("$url/api/v2/$resource", $result);
    }

    /**
     * Get base Url to call the service
     * Context: Base url and resourceUri defined
     */
    public function test_getUrl_3()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->setMethods(["baseUrl", "resourceUri", "all","find","findByField","create","update","delete"])
            ->getMock();

        $object->method("all")->willReturn("");
        $object->method("find")->willReturn("");
        $object->method("findByField")->willReturn("");
        $object->method("create")->willReturn("");

        $url = "http://remo-service.com";
        $serviceUri = "service-path";
        $resource = "resource";
        $object->method("baseUrl")->willReturn($url);
        $object->method("resourceUri")->willReturn($serviceUri);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invoke($object);
        self::assertEquals("$url/api/v1/$serviceUri", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, [$resource, 2]);
        self::assertEquals("$url/api/v2/$serviceUri/$resource", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, ["/$resource", 2]);
        self::assertEquals("$url/api/v2/$serviceUri/$resource", $result);
    }

    /**
     * Get base Url to call the service
     * Context: Base url, resourceUri and resource uri defined. Testing slash concatenation
     */
    public function test_getUrl_4()
    {
        $uow = new MockUnitOfWork();
        $object = $this->getMockBuilder(RemoteRepository::class)
            ->setConstructorArgs([$uow])
            ->setMethods(["baseUrl", "resourceUri", "all","find","findByField","create","update","delete"])
            ->getMock();

        $object->method("all")->willReturn("");
        $object->method("find")->willReturn("");
        $object->method("findByField")->willReturn("");
        $object->method("create")->willReturn("");

        $url = "http://remo-service.com";
        $serviceUri = "/service-path/";
        $resource = "resource";

        $expected = "http://remo-service.com/api/v1/service-path";
        $expectedV2 = "http://remo-service.com/api/v2/service-path";
        $object->method("baseUrl")->willReturn($url);
        $object->method("resourceUri")->willReturn($serviceUri);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invoke($object);
        self::assertEquals("$expected/", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, ["/$resource", 2]);
        self::assertEquals("$expectedV2/resource", $result);

        $method = self::getMethod("getUrl", RemoteRepository::class);
        $result = $method->invokeArgs($object, [$resource, 2]);
        self::assertEquals("$expectedV2/resource", $result);
    }
}
