<?php

namespace Webkul\StripeConnect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\StripeConnect\Http\Controllers\Controller;
use Webkul\StripeConnect\Repositories\StripeConnectRepository as StripeConnect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Stripe\Stripe;

/**
 * Seller Registration controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SellerRegistrationController extends Controller
{
    /**
     * To hold the StripeConnect Repository Instance
     */
    protected $stripeConnect;

    public function __construct(StripeConnect $stripeConnect)
    {
        $this->stripeConnect = $stripeConnect;

        Stripe::setApiKey(env('STRIPE_TEST_SECRET_KEY'));
    }

    public function index()
    {
        return view('stripe::connect');
    }

    /**
     * To process the retrieved token after the seller's onboarding
     * on platform account
     */
    public function retrieveToken()
    {
        if (! request()->has('error')) {
            $scope = request()->input('scope');
            $code = request()->input('code');

            $client = new Client(); //GuzzleHttp\Client
            $result = $client->post('https://connect.stripe.com/oauth/token', [
                'auth' => [env('STRIPE_TEST_SECRET_KEY'), ''],
                'form_params' => [
                    'code' => $code,
                    'grant_type' => 'authorization_code'
                ]
            ]);

            $decoded = json_decode($result->getBody()->getContents());
            $access_token = $decoded->access_token;
            $refresh_token = $decoded->refresh_token;
            $publishable_key = $decoded->stripe_publishable_key;
            $stripe_user_id = $decoded->stripe_user_id;

            $result = $this->stripeConnect->create([
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'stripe_publishable_key' => $publishable_key,
                'stripe_user_id' => $stripe_user_id
            ]);

            if ($result) {
                session()->flash('success', 'Your Stripe account is successfully integrated with the platform');
            } else {
                session()->flash('error', 'There was some problem in onboarding your account');
            }
        } else {
            session()->flash('error', request()->input('error_description'));
        }

        return redirect()->route('admin.stripe.seller');
    }

    public function revokeAccess()
    {
        $stripeConnectDetails = $this->stripeConnect->findWhere([
            'company_id' => \Company::getCurrent()->id
        ])->first();

        $client = new Client(); //GuzzleHttp\Client
        $result = $client->post('https://connect.stripe.com/oauth/deauthorize', [
            'auth' => [env('STRIPE_TEST_SECRET_KEY'), ''],
            'form_params' => [
                'client_id' => core()->getConfigData('stripe.connect.details.clientid'),
                'stripe_user_id' => $stripeConnectDetails->stripe_user_id
            ]
        ]);

        if ($result->getStatusCode() == 200) {
            $stripeConnectDetails->delete();

            session()->flash('info', 'Your stripe account has been successfully revoked from the platform');
        } else {
            session()->flash('error', $result->getBody());
        }

        return redirect()->route('admin.stripe.seller');
    }
}