<?php

namespace Tests\Functional\Customer;

use Faker\Factory;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use FunctionalTester;

class CustomerCest
{
    public $fields = [];

    public function _before(FunctionalTester $I): void
    {
        $I->useDefaultTheme();
    }

    public function updateCustomerProfile(FunctionalTester $I): void
    {
        $customer = $I->loginAsCustomer();

        $I->amOnPage('/');

        // $I->click('Profile');
        // $I->click('Edit');
        // $I->selectOption('gender', 'Other');
        // $I->click('Update Profile');

        // $I->dontSeeInSource('The old password does not match.');
        // $I->seeInSource('Profile updated successfully.');

        // $I->seeRecord(Customer::class, [
        //     'id'     => $customer->id,
        //     'gender' => 'Other',
        // ]);
    }

    public function updateCustomerAddress(FunctionalTester $I): void
    {
        // Instantiate a european faker factory to have the vat provider available
        $faker = Factory::create('at_AT');

        $formCssSelector = '#customer-address-form';

        $I->loginAsCustomer();

        $I->amOnPage('/');

        // $I->click('Profile');
        // $I->click('Address');
        // $I->click('Add Address');

        $this->fields = [
            'company_name' => $faker->company,
            'first_name'   => $faker->firstName,
            'last_name'    => $faker->lastName,
            'vat_id'       => 'INVALIDVAT',
            'address1[]'   => $faker->streetAddress,
            'country'      => $faker->countryCode,
            'state'        => $faker->state,
            'city'         => $faker->city,
            'postcode'     => $faker->postcode,
            'phone'        => $faker->phoneNumber,
        ];

        foreach ($this->fields as $key => $value) {
            // the following fields are rendered via javascript so we ignore them:
            if (! in_array($key, [
                'country',
                'state',
            ])) {
                $selector = 'input[name="' . $key . '"]';
                // $I->fillField($selector, $value);
            }
        }

        $I->wantTo('Ensure that the company_name field is being displayed');
        // $I->seeElement('.account-table-content > div:nth-child(2) > input:nth-child(2)');

        // we need to use this css selector to hit the correct <form>. There is another one at the
        // page header (search)
        // $I->submitForm($formCssSelector, $this->fields);
        // $I->seeInSource('The given vat id has a wrong format');

        $I->wantTo('enter a valid vat id');
        $this->fields['vat_id'] = $faker->vat(false);

        // $I->submitForm($formCssSelector, $this->fields);

        // $I->seeInSource('Address have been successfully added.');

        $this->assertCustomerAddress($I);

        $I->wantTo('Update the created customer address again');

        // $I->click('Edit');

        $oldcompany = $this->fields['company_name'];
        $this->fields['company_name'] = $faker->company;

        // $I->submitForm($formCssSelector, $this->fields);

        // $I->seeInSource('Address updated successfully.');

        $I->dontSeeRecord(CustomerAddress::class, [
            'company_name' => $oldcompany,
        ]);

        // $this->assertCustomerAddress($I);
    }

    /**
     * @param FunctionalTester $I
     */
    private function assertCustomerAddress(FunctionalTester $I): void
    {
        // $I->seeRecord(CustomerAddress::class, [
        //     'company_name' => $this->fields['company_name'],
        //     'first_name'   => $this->fields['first_name'],
        //     'last_name'    => $this->fields['last_name'],
        //     'vat_id'       => $this->fields['vat_id'],
        //     'address1'     => $this->fields['address1[]'],
        //     'country'      => $this->fields['country'],
        //     'state'        => $this->fields['state'],
        //     'city'         => $this->fields['city'],
        //     'phone'        => $this->fields['phone'],
        //     'postcode'     => $this->fields['postcode'],
        // ]);
    }
}