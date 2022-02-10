<?php

use Actions\CartAction;
use Actions\CleanAction;
use Actions\ProductAction;
use Actions\ProductActionContract;
use Laravel\Sanctum\Sanctum;
use Webkul\Customer\Models\Customer;

/**
 * Inherited methods.
 *
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
class ApiTester extends \Codeception\Actor implements ProductActionContract
{
    use _generated\ApiTesterActions, CartAction, CleanAction, ProductAction;

    /**
     * Sanctum authenticated customer.
     *
     * @return \Webkul\Customer\Models\Customer
     */
    public function amSanctumAuthenticatedCustomer()
    {
        return Sanctum::actingAs(
            Customer::factory()->create(),
            ['*']
        );
    }

    /**
     * Create token for sanctum authenticated customer.
     *
     * @param  \Webkul\Customer\Models\Customer  $customer
     * @return string
     */
    public function amCreatingTokenForSanctumAuthenticatedCustomer(Customer $customer)
    {
        return $this->grabTokenFromSanctumGeneratedString(
            $customer->createToken($this->fake()->company)->plainTextToken
        );
    }

    /**
     * Set all necessary headers, if token is passed then bearable authentication header
     * will pass.
     *
     * @param  optional|string  $token
     * @return void
     */
    public function haveAllNecessaryHeaders($token = null)
    {
        $this->haveHttpHeader('Accept', 'application/json');

        $this->haveHttpHeader('Content-Type', 'application/json');

        if ($token) {
            $this->amBearerAuthenticated($token);
        }
    }

    /**
     * Check all necessary success response.
     *
     * @return void
     */
    public function seeAllNecessarySuccessResponse()
    {
        $this->seeResponseCodeIsSuccessful();

        $this->seeResponseIsJson();
    }

    /**
     * Get JSON decoded response.
     *
     * @return mixed
     */
    public function grabJsonDecodedResponse()
    {
        return json_decode($this->grabResponse());
    }

    /**
     * Get token from response.
     *
     * @return string
     */
    public function grabTokenFromResponse()
    {
        $idAndToken = $this->grabDataFromResponseByJsonPath('token')[0];

        return $this->grabTokenFromSanctumGeneratedString($idAndToken);
    }

    /**
     * Get token from sanctum generated string.
     *
     * @return string
     */
    public function grabTokenFromSanctumGeneratedString($idAndToken)
    {
        $idAndToken = explode('|', $idAndToken);

        return $idAndToken[1];
    }
}
