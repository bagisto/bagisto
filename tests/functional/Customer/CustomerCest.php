<?php

namespace Tests\Functional\Customer;

use Faker\Factory;
use FunctionalTester;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

class CustomerCest
{
    /**
     * Faker factory.
     *
     * @var Faker\Factory
     */
    public $faker;

    /**
     * Address fields.
     *
     * @var array
     */
    public $fields = [];

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * Instantiate a european faker factory to have the vat provider available.
         */
        $this->faker = Factory::create('at_AT');
    }

    /**
     * Before method.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function _before(FunctionalTester $I): void
    {
        $I->useDefaultTheme();
    }

    /**
     * Test update customer profile.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function updateCustomerProfile(FunctionalTester $I): void
    {
        $customer = $I->loginAsCustomer();
        $customer->first_name = $I->cleanField($customer->first_name);
        $customer->last_name = $I->cleanField($customer->last_name);
        $customer->save();

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

    /**
     * Update customer address.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function updateCustomerAddress(FunctionalTester $I): void
    {
        $formCssSelector = '#customer-address-form';

        $I->loginAsCustomer();

        $I->amOnPage('/');
        $I->click('Profile');
        $I->click('Address');
        $I->click('Add Address');

        $this->setFields($I);

        foreach ($this->fields as $key => $value) {
            /**
             * The following fields are rendered via javascript so we ignore them.
             */
            if (! in_array($key, [
                'country',
                'state',
            ])) {
                $selector = 'input[name="' . $key . '"]';
                $I->fillField($selector, $value);
            }
        }

        $I->wantTo('Ensure that the `company_name` field is being displayed.');
        $I->seeElement('.account-table-content > div:nth-child(2) > input:nth-child(2)');

        /**
         * We need to use this css selector to hit the correct <form>. There is another one at the
         * page header (search).
         */
        $I->submitForm($formCssSelector, $this->fields);
        $I->seeInSource('The given vat id has a wrong format');

        $I->wantTo('Enter a valid vat id.');
        $this->fields['vat_id'] = $this->faker->vat(false);

        $I->submitForm($formCssSelector, $this->fields);

        $I->seeInSource('Address have been successfully added.');

        $this->assertCustomerAddress($I);

        $I->wantTo('Update the created customer address again.');

        $I->click('Edit');

        $oldcompany = $this->fields['company_name'];
        $this->fields['company_name'] = $I->cleanField($this->faker->company);

        $I->submitForm($formCssSelector, $this->fields);

        $I->seeInSource('Address updated successfully.');

        $I->dontSeeRecord(CustomerAddress::class, [
            'company_name' => $oldcompany,
        ]);

        $this->assertCustomerAddress($I);
    }

    /**
     * Assert customer address.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    private function assertCustomerAddress(FunctionalTester $I): void
    {
        $I->seeRecord(CustomerAddress::class, [
            'company_name' => $this->fields['company_name'],
            'first_name'   => $this->fields['first_name'],
            'last_name'    => $this->fields['last_name'],
            'vat_id'       => $this->fields['vat_id'],
            'address1'     => $this->fields['address1[]'],
            'country'      => $this->fields['country'],
            'state'        => $this->fields['state'],
            'city'         => $this->fields['city'],
            'phone'        => $this->fields['phone'],
            'postcode'     => $this->fields['postcode'],
        ]);
    }

    /**
     * Set fields.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    private function setFields(FunctionalTester $I)
    {
        $this->fields = $I->cleanAllFields([
            'company_name' => $this->faker->company,
            'first_name'   => $this->faker->firstName,
            'last_name'    => $this->faker->lastName,
            'vat_id'       => 'INVALIDVAT',
            'address1[]'   => $this->faker->streetAddress,
            'country'      => $this->faker->countryCode,
            'state'        => $this->faker->word,
            'city'         => $this->faker->city,
            'postcode'     => $this->faker->postcode,
            'phone'        => $this->faker->phoneNumber,
        ]);
    }
}
