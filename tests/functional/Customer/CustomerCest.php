<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

class CustomerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function updateCustomerProfile(FunctionalTester $I)
    {
        $I->loginAsCustomer();

        $I->amOnPage('/');

        $I->click('Profile');

        $I->click('Edit');

        $I->selectOption('gender', 'Other');

        $I->click('Update Profile');

        $I->dontSeeInSource('The old password does not match.');

        $I->seeInSource('Profile updated successfully.');

        $I->seeRecord(Customer::class, [
            'id'     => $customer->id,
            'gender' => 'Other',
        ]);
    }

    public function updateCustomerAddress(FunctionalTester $I)
    {
        $faker = Faker\Factory::create();

        $formCssSelector = '#customer-address-form';

        $I->loginAsCustomer();

        $I->amOnPage('/');

        $I->click('Profile');

        $I->click('Address');

        $I->click('Add Address');

        $fields = [
            'company_name' => $faker->company,
            'vat_id'       => $faker->randomNumber(9),
            'address1[]'   => $faker->streetAddress,
            'country'      => $faker->countryCode,
            'state'        => $faker->state,
            'city'         => $faker->city,
            'postcode'     => $faker->postcode,
            'phone'        => $faker->phoneNumber,
        ];

        foreach ($fields as $key => $value) {
            // the following fields are being rendered via javascript so we ignore them:
            if (! in_array($key, [
                'country',
                'state',
            ])) {
                $selector = 'input[name="' . $key . '"]';
                $I->fillField($selector, $value);
            }
        }

        $I->wantTo('Ensure that the company_name field is being displayed');
        $I->seeElement('.account-table-content > div:nth-child(2) > input:nth-child(2)');

        // we need to use this css selector to hit the correct <form>. There is another one at the
        // page header (search)
        $I->submitForm($formCssSelector, $fields);
        $I->seeInSource('The given vat id has a wrong format');

        // valid vat id:
        $fields['vat_id'] = 'DE123456789';

        $I->submitForm($formCssSelector, $fields);

        $I->seeInSource('Address have been successfully added.');

        $I->seeInSource('Address have been successfully added.');

        $I->seeRecord(CustomerAddress::class, [
            'company_name' => $fields['company_name'],
            'vat_id'       => $fields['vat_id'],
            'address1'     => $fields['address1[]'],
            'country'      => $fields['country'],
            'state'        => $fields['state'],
            'city'         => $fields['city'],
            'phone'        => $fields['phone'],
            'postcode'     => $fields['postcode'],
        ]);

        $I->wantTo('Update the created customer address again');

        $I->click('Edit');

        $oldcompany = $fields['company_name'];
        $fields['company_name'] = $faker->company;

        $I->submitForm($formCssSelector, $fields);

        $I->seeInSource('Address updated successfully.');

        $I->dontSeeRecord(CustomerAddress::class, [
            'company_name' => $oldcompany,
        ]);

        $I->seeRecord(CustomerAddress::class, [
            'company_name' => $fields['company_name'],
            'postcode'     => $fields['postcode'],
        ]);
    }
}