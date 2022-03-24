<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Illuminate\Support\Facades\Notification;
use Webkul\Customer\Models\Customer;

class AuthCest extends CustomerCest
{
    public function testForRegisterTheCustomer(ApiTester $I)
    {
        $I->haveAllNecessaryHeaders();

        $I->sendPost($this->getVersionRoute('customer/register'), [
            'first_name'            => $I->fake()->firstName(),
            'last_name'             => $I->fake()->lastName(),
            'email'                 => $I->fake()->email(),
            'password'              => $password = $I->fake()->password,
            'password_confirmation' => $password,
        ]);

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForLoginTheCustomer(ApiTester $I)
    {
        $customer = $I->have(Customer::class);

        $I->haveAllNecessaryHeaders();

        $I->sendPost($this->getVersionRoute('customer/login'), [
            'email'       => $customer->email,
            'password'    => json_decode($customer->notes)->plain_password,
            'device_name' => $I->fake()->company,
        ]);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name' => $customer->first_name,
                'last_name'  => $customer->last_name,
                'gender'     => $customer->gender,
                'email'      => $customer->email,
            ],
        ]);
    }

    public function testForFetchingTheLoggedInCustomer(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        $I->sendGet($this->getVersionRoute('customer/get'));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name' => $customer->first_name,
                'last_name'  => $customer->last_name,
                'gender'     => $customer->gender,
                'email'      => $customer->email,
            ],
        ]);
    }

    public function testForUpdatingTheLoggedInCustomer(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        $I->sendPut($this->getVersionRoute('customer/profile'), [
            'first_name' => $changedFirstName = $I->fake()->firstName(),
            'last_name'  => $changedLastName = $I->fake()->lastName(),
            'gender'     => $customer->gender,
            'email'      => $customer->email,
        ]);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name' => $changedFirstName,
                'last_name'  => $changedLastName,
                'gender'     => $customer->gender,
                'email'      => $customer->email,
            ],
        ]);

        $I->dontSeeResponseContainsJson([
            'data' => [
                'first_name' => $customer->first_name,
                'last_name'  => $customer->last_name,
            ],
        ]);
    }

    public function testForLogoutTheCustomer(ApiTester $I)
    {
        $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        $I->sendPost($this->getVersionRoute('customer/logout'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForPasswordResetLink(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        Notification::fake();

        $I->sendPost($this->getVersionRoute('customer/forgot-password'), [
            'email' => $customer->email,
        ]);

        $I->seeAllNecessarySuccessResponse();
    }
}
