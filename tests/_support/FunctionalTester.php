<?php

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Webkul\User\Models\Admin;


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * Login as default administrator
     */
    public function loginAsAdmin(): void
    {
        $I = $this;

        Auth::guard('admin')->login($I->grabRecord(Admin::class, ['email' => 'admin@example.com']));
        $I->seeAuthentication('admin');
    }

    /**
     * Go to a specific route and check if admin guard is applied on it
     *
     * @param string     $name name of the route
     * @param array|null $params params the route will be created with
     */
    public function amOnAdminRoute(string $name, array $params = null): void
    {
        $I = $this;
        $I->amOnRoute($name, $params);
        $I->seeCurrentRouteIs($name);

        /** @var RouteCollection $routes */
        $routes = Route::getRoutes();
        $middlewares = $routes->getByName($name)->middleware();
        $I->assertContains('admin', $middlewares, 'check that admin middleware is applied');
    }

    /**
     * Set specific Webkul/Core configuration keys to a given value
     *
     * // TODO: change method as soon as there is a method to set core config data
     *
     * @param $data array containing 'code => value' pairs
     * @return void
     */
    public function setConfigData($data): void {
        foreach ($data as $key => $value) {
            if (DB::table('core_config')->where('code', '=', $key)->exists()) {
                DB::table('core_config')->where('code', '=', $key)->update(['value' => $value]);
            } else {
                DB::table('core_config')->insert([
                    'code'       => $key,
                    'value'      => $value,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
