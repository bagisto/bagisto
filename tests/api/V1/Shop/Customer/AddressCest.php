<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Webkul\Customer\Models\CustomerAddress;

class AddressCest extends CustomerCest
{
    public function testForFetchingAllTheCustomerAddresses(ApiTester $I)
    {
        $howManyAddresses = $I->fake()->randomDigitNotZero();

        $customer = $I->amSanctumAuthenticatedCustomer();

        $I->haveMultiple(CustomerAddress::class, $howManyAddresses, [
            'customer_id' => $customer->id,
        ]);

        $I->haveAllNecessaryHeaders();

        $I->sendGet($this->getVersionRoute('customer/addresses'));

        $I->seeAllNecessarySuccessResponse();

        $response = $I->grabJsonDecodedResponse();

        $I->assertEquals($howManyAddresses, count($response->data));
    }

    public function testForStoringTheCustomerAddress(ApiTester $I)
    {
        $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        $fields = $I->cleanAllFields([
            'first_name'   => $I->fake()->firstName,
            'last_name'    => $I->fake()->lastName,
            'address1'     => [$I->fake()->streetAddress],
            'company_name' => $I->fake()->company,
            'country'      => $I->fake()->countryCode,
            'state'        => $I->fake()->word,
            'city'         => $I->fake()->city,
            'postcode'     => $I->fake()->postcode,
            'phone'        => $I->fake()->phoneNumber,
        ]);

        $I->sendPost($this->getVersionRoute('customer/addresses'), $fields);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name'   => $fields['first_name'],
                'last_name'    => $fields['last_name'],
                'address1'     => $fields['address1'],
                'company_name' => $fields['company_name'],
                'country'      => $fields['country'],
                'state'        => $fields['state'],
                'city'         => $fields['city'],
                'postcode'     => $fields['postcode'],
                'phone'        => $fields['phone'],
            ],
        ]);
    }

    public function testForFetchingTheCustomerAddress(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $customerAddress = $I->have(CustomerAddress::class, [
            'customer_id'  => $customer->id,
            'first_name'   => $I->fake()->firstName,
            'last_name'    => $I->fake()->lastName,
            'address1'     => $I->fake()->streetAddress,
            'company_name' => $I->fake()->company,
            'country'      => $I->fake()->countryCode,
            'state'        => $I->fake()->word,
            'city'         => $I->fake()->city,
            'postcode'     => $I->fake()->postcode,
            'phone'        => $I->fake()->phoneNumber,
        ]);

        $I->haveAllNecessaryHeaders();

        $I->sendGet($this->getVersionRoute('customer/addresses/' . $customerAddress->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name'   => $customerAddress->first_name,
                'last_name'    => $customerAddress->last_name,
                'address1'     => [$customerAddress->address1],
                'company_name' => $customerAddress->company_name,
                'country'      => $customerAddress->country,
                'state'        => $customerAddress->state,
                'city'         => $customerAddress->city,
                'postcode'     => $customerAddress->postcode,
                'phone'        => $customerAddress->phone,
            ],
        ]);
    }

    public function testForUpdatingTheCustomerAddress(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $customerAddress = $I->have(CustomerAddress::class, [
            'customer_id'  => $customer->id,
            'first_name'   => $I->fake()->firstName,
            'last_name'    => $I->fake()->lastName,
            'address1'     => $I->fake()->streetAddress,
            'company_name' => $I->fake()->company,
            'country'      => $I->fake()->countryCode,
            'state'        => $I->fake()->word,
            'city'         => $I->fake()->city,
            'postcode'     => $I->fake()->postcode,
            'phone'        => $I->fake()->phoneNumber,
        ]);

        $I->haveAllNecessaryHeaders();

        $fields = $I->cleanAllFields([
            'first_name'   => $I->fake()->firstName,
            'last_name'    => $I->fake()->lastName,
            'address1'     => [$customerAddress->address1],
            'company_name' => $customerAddress->company_name,
            'country'      => $customerAddress->country,
            'state'        => $customerAddress->state,
            'city'         => $customerAddress->city,
            'postcode'     => $customerAddress->postcode,
            'phone'        => $customerAddress->phone,
        ]);

        $I->sendPut($this->getVersionRoute('customer/addresses/' . $customerAddress->id), $fields);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'first_name'   => $fields['first_name'],
                'last_name'    => $fields['last_name'],
                'address1'     => $fields['address1'],
                'company_name' => $fields['company_name'],
                'country'      => $fields['country'],
                'state'        => $fields['state'],
                'city'         => $fields['city'],
                'postcode'     => $fields['postcode'],
                'phone'        => $fields['phone'],
            ],
        ]);

        $I->dontSeeResponseContainsJson([
            'data' => [
                'first_name' => $customerAddress->firstName,
                'last_name'  => $customerAddress->lastName,
            ],
        ]);
    }

    public function testForDeletingTheCustomerAddress(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $customerAddress = $I->have(CustomerAddress::class, [
            'customer_id' => $customer->id,
        ]);

        $I->haveAllNecessaryHeaders();

        $I->sendDelete($this->getVersionRoute('customer/addresses/' . $customerAddress->id));

        $I->seeAllNecessarySuccessResponse();
    }
}
