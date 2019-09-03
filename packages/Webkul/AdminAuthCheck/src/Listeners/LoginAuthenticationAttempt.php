<?php

namespace Webkul\AdminAuthCheck\Listeners;

use Company;
use Webkul\SAASCustomizer\Repositories\SuperAdminRepository as SuperAdmin;

/**
 * Login Authentication Attempt Event handler
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class LoginAuthenticationAttempt
{
    protected $superAdmin;

    public function __construct(SuperAdmin $superAdmin) {
        $this->superAdmin = $superAdmin;
    }

    public function handle()
    {
        $company = Company::getCurrent();

        if (! auth()->guard('super-admin')->check()) {
            $credentials = request()->all();
            $credentials['company_id'] = $company->id;

            unset($credentials['_token']);

            if (auth()->guard('admin')->check()) {
                $admin = auth()->guard('admin')->user();

                if (Company::getCurrent()->id != $admin->company_id) {
                    auth()->guard('admin')->logout();

                    throw new \Exception('invalid_admin_login', 400);
                }
            } else if (auth()->guard('customer')->check()) {
                $customer = auth()->guard('customer')->user();

                if (Company::getCurrent()->id != $customer->company_id) {
                    auth()->guard('customer')->logout();

                    if (! auth()->guard('customer')->attempt($credentials)) {

                        throw new \Exception('invalid_customer_login', 400);
                    }
                }
            }
        }
    }
}