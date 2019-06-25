<?php
/**
 * @author Ernesto Baez
 * @author Ernesto Baez  05/06/19 9:29 AM
 */

namespace ErnestoBaezF\L5CoreToolbox\tests\Unit\Http\Middleware;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ErnestoBaezF\L5CoreToolbox\Http\Middleware\Localization;
use ErnestoBaezF\L5CoreToolbox\Test\Environment\TestCase;
use Illuminate\Support\Facades\Config;

class LocalizationTest extends TestCase
{
    /**
     * Set application locale when header X-localization is set in the request
     */
    public function test_handle_1()
    {
        $user = app(Config::get("auth.providers.users.model"));
        Auth::shouldReceive("user")->andReturn($user);

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
     */
    public function test_handle_2()
    {
        $user = app(Config::get("auth.providers.users.model"));

        if (!method_exists ($user, "setLocale")) {
            self::assertTrue(true);

            return;
        }

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
     */
    public function test_handle_3()
    {
        $user = app(Config::get("auth.providers.users.model"));

        if (!method_exists ($user, "setLocale")) {
            self::assertTrue(true);

            return;
        }

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
