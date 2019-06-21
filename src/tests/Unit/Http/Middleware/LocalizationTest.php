<?php
/**
 * @author Ernesto Baez 
 * @author Ernesto Baez  05/06/19 9:29 AM
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Http\Middleware;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ErnestoBaezF\L5CoreToolbox\Http\Middleware\Localization;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;

class LocalizationTest extends TestCase
{
    /**
     * Set application locale when header X-localization is set in the request
     *
     * @throws \ReflectionException
     */
    public function test_handle_1()
    {
        Auth::shouldReceive("user")->andReturn(new User());

        $request = new Request([], [], [], [], [], ["HTTP_X-localization" => "fr"]);
        $object = $this->getMockBuilder(Localization::class)->getMock();

        $method = self::getMethod("handle", Localization::class);
        $result = $method->invokeArgs($object, [$request, function($param) {
            return $param;
        }]);

        self::assertEquals($request, $result);
        self::assertEquals("fr", $this->app->getLocale());
    }

    /**
     * Set application locale when no X-localization header is present and the user has a preferred locale
     *
     * @throws \ReflectionException
     */
    public function test_handle_2()
    {
        $user = new User();
        $user->setLocale("it");

        Auth::shouldReceive("user")->andReturn($user);

        $request = new Request();
        $object = $this->getMockBuilder(Localization::class)->getMock();

        $method = self::getMethod("handle", Localization::class);
        $result = $method->invokeArgs($object, [$request, function($param) {
            return $param;
        }]);

        self::assertEquals($request, $result);
        self::assertEquals("it", $this->app->getLocale());
    }

    /**
     * Set application locale when no X-localization header is present and the user has no preferred locale
     *
     * @throws \ReflectionException
     */
    public function test_handle_3()
    {
        $user = new User();
        $user->setLocale("");

        Auth::shouldReceive("user")->andReturn(new User());

        $request = new Request();
        $object = $this->getMockBuilder(Localization::class)->getMock();

        $method = self::getMethod("handle", Localization::class);
        $result = $method->invokeArgs($object, [$request, function($param) {
            return $param;
        }]);

        self::assertEquals($request, $result);
        self::assertEquals("en", $this->app->getLocale());
    }

    /**
     * Set application locale when no X-localization header is present and there is no user
     *
     * @throws \ReflectionException
     */
    public function test_handle_4()
    {
        Auth::shouldReceive("user")->andReturn(null);

        $request = new Request();
        $object = $this->getMockBuilder(Localization::class)->getMock();

        $method = self::getMethod("handle", Localization::class);
        $result = $method->invokeArgs($object, [$request, function($param) {
            return $param;
        }]);

        self::assertEquals($request, $result);
        self::assertEquals("en", $this->app->getLocale());
    }
}
