<?php

namespace Tests\Functional\Admin\Customer;


use FunctionalTester;
use Webkul\Customer\Models\Customer;


class CustomerCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $customer = $I->have(Customer::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "navbar-left")]');
        $I->seeCurrentRouteIs('admin.customer.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.customer.index');
        $I->see($customer->id, '//script[@type="text/x-template"]');
        $I->see($customer->last_name, '//script[@type="text/x-template"]');
    }

    public function testCreate(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.customer.index');

        $I->click(__('admin::app.customers.customers.add-title'), '//*[contains(@class, "page-action")]');
        $I->seeCurrentRouteIs('admin.customer.create');

        $I->click(__('admin::app.customers.customers.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testData = $this->fillForm($I);
        $I->click(__('admin::app.customers.customers.save-btn-title'), '//*[contains(@class, "page-action")]');

        $I->dontSeeFormErrors();
        $I->seeCurrentRouteIs('admin.customer.index');
        $I->seeRecord(Customer::class, $testData);
    }

    /**
     * @param FunctionalTester $I
     *
     * @return array with the test-data
     */
    private function fillForm(FunctionalTester $I): array
    {
        $testData = [
            'first_name' => $I->fake()->firstName,
            'last_name'  => $I->fake()->lastName,
            'gender'     => $I->fake()->randomElement([
                'Male',
                'Female',
                'Other',
            ]),
            'email'      => $I->fake()->email,
        ];

        $I->fillField('first_name', $testData['first_name']);
        $I->fillField('last_name',  $testData['last_name']);
        $I->selectOption('gender',  $testData['gender']);
        $I->fillField('email',      $testData['email']);

        return $testData;
    }
}
