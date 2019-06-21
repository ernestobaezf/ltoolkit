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

}