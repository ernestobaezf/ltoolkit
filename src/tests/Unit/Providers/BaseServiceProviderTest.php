<?php
/**
 * @author Ernesto Baez 
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Providers;


use Illuminate\Support\Facades\Gate;
use ErnestoBaezF\L5CoreToolbox\Providers\BaseServiceProvider;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class BaseServiceProviderTest extends TestCase
{
    /**
     * Register Policies
     */
    public function test_registerPolicies()
    {
        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getPolicies"])
            ->getMock();

        $object->method("getPolicies")->willReturn(["className" => "policyName"]);

        $method = self::getMethod("registerPolicies", BaseServiceProvider::class);
        $method->invoke($object);

        self::assertTrue(true);
    }

    /**
     * Register Gates
     */
    public function test_registerGates()
    {
        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getGates"])
            ->getMock();

        $object->method("getGates")->willReturn([]);

        $method = self::getMethod("registerGates", BaseServiceProvider::class);
        self::assertNull($method->invoke($object));

        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getGates"])
            ->getMock();

        $object->method("getGates")->willReturn(["className" => "policyName"]);

        $method = self::getMethod("registerGates", BaseServiceProvider::class);
        $method->invoke($object);

        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->setMethods(["getGates"])
            ->getMock();

        $object->method("getGates")->willReturn("policyName");

        $method = self::getMethod("registerGates", BaseServiceProvider::class);
        $method->invoke($object);
    }

    /**
     * Register Gates
     */
    public function test_getPackageNamespace()
    {
        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("getPackageNamespace", BaseServiceProvider::class);
        $result = $method->invoke($object);

        $method = self::getMethod("getPackageName", BaseServiceProvider::class);
        $name = $method->invoke($object);

        self::assertEquals("Packages\\$name", $result);
    }

    /**
     * Register Gates
     */
    public function test_getPath()
    {
        $object = $this->getMockBuilder(BaseServiceProvider::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->disableOriginalClone()
            ->getMock();

        $method = self::getMethod("getPackageName", BaseServiceProvider::class);
        $name = $method->invoke($object);

        $method = self::getMethod("getPath", BaseServiceProvider::class);
        $result = $method->invoke($object, "data");

        self::assertEquals(base_path("packages".DIRECTORY_SEPARATOR.$name."/data"), $result);

        $result = $method->invoke($object, "/data");
        self::assertEquals(base_path("packages".DIRECTORY_SEPARATOR.$name."/data"), $result);
    }

}
