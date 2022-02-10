<?php

namespace Tests\Functional\Admin\Customer;

use FunctionalTester;
use Webkul\Customer\Models\Customer;

class AddressCest
{
    /**
     * Test address index page.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testIndex(FunctionalTester $I): void
    {
        $customer = $I->have(Customer::class);

        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.customer.edit', [$customer->id]);
        $I->seeCurrentRouteIs('admin.customer.edit');

        $I->sendAjaxGetRequest(route('admin.customer.addresses.index', $customer->id));
        $I->seeResponseCodeIsSuccessful();
    }

    /**
     * Test create address page.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testCreate(FunctionalTester $I): void
    {
        $customer = $I->have(Customer::class);

        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.customer.edit', [$customer->id]);

        $I->click(__('admin::app.customers.addresses.create-btn-title'), '//*[contains(@class, "page-content")]');
        $I->seeCurrentRouteIs('admin.customer.addresses.create');

        $I->click(__('admin::app.customers.addresses.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        // To Do (@devansh-webkul): Simulate this...
        // $addressData = $this->generateAddressData($I);
        // $this->fillForm($I, $addressData);

        // $I->click(__('admin::app.customers.addresses.save-btn-title'), '//*[contains(@class, "page-action")]');
        // $I->dontSeeFormErrors();

        // $I->seeCurrentRouteIs('admin.customer.addresses.index');
        // $I->seeRecord(Customer::class, $addressData);
    }

    /**
     * Generate address data.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    private function generateAddressData(FunctionalTester $I)
    {
        return $I->cleanAllFields([
            'first_name' => $I->fake()->firstName,
            'last_name'  => $I->fake()->lastName,
            'address1'   => $I->fake()->streetAddress,
            'country'    => $I->fake()->countryCode,
            'state'      => $I->fake()->word,
            'city'       => $I->fake()->city,
            'postcode'   => $I->fake()->postcode,
            'phone'      => $I->fake()->phoneNumber,
        ]);
    }

    /**
     * Fill form data.
     *
     * @param  FunctionalTester  $I
     * @param  array  $addressData
     * @return void
     */
    private function fillForm(FunctionalTester $I, array $addressData)
    {
        $I->fillField('first_name', $addressData['first_name']);

        $I->fillField('last_name', $addressData['last_name']);

        $I->fillField('address1[]', $addressData['address1']);

        $I->fillField('city', $addressData['city']);

        $I->selectOption('country', $addressData['country']);

        $I->selectOption('state', $addressData['state']);

        $I->fillField('postcode', $addressData['postcode']);

        $I->fillField('phone', $addressData['phone']);
    }
}
