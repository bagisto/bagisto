<?php

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Webkul\User\Models\Admin;
use Webkul\Customer\Models\Customer;

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
     * Set the logged in user to the admin identity.
     *
     * @param \Webkul\User\Models\Admin|null $admin
     *
     * @throws \Exception
     * @returns Admin
     */
    public function loginAsAdmin(Admin $admin = null): Admin
    {
        $I = $this;

        if (! $admin) {
            $admin = $I->grabRecord(Admin::class, ['email' => 'admin@example.com']);
        }

        if (! $admin) {
            throw new \Exception(
                'Admin user not found in database. Please ensure Seeders are executed');
        }

        Auth::guard('admin')
            ->login($admin);

        $I->seeAuthentication('admin');

        return $admin;
    }

    /**
     * Set the logged in user to the customer identity.
     *
     * @param \Webkul\User\Models\Customer|null $customer
     *
     * @throws \Exception
     * @returns Customer
     */
    public function loginAsCustomer(Customer $customer = null): Customer
    {
        $I = $this;

        if (! $customer) {
            $customer = $I->have(Customer::class);
        }

        Auth::guard('customer')
            ->login($customer);

        $I->seeAuthentication('customer');

        return $customer;
    }

    /**
     * @param string $name
     */
    public function amOnAdminRoute(string $name)
    {
        $I = $this;
        $I->amOnRoute($name);
        $I->seeCurrentRouteIs($name);

        /** @var RouteCollection $routes */
        $routes = Route::getRoutes();
        $middlewares = $routes->getByName($name)->middleware();
        $I->assertContains('admin', $middlewares, 'check that admin middleware is applied');
    }
}
