<?php

namespace Webkul\Stripe\Helpers;

use Stripe\PaymentIntent;
use Webkul\Checkout\Facades\Cart;
use Webkul\Stripe\Repositories\StripeCartRepository as StripeCart;
use Webkul\Stripe\Repositories\StripeRepository;

class Helper
{
    /**
     *  Create a new helper instance.
     *
     * @param  \Webkul\Stripe\Repositories\StripeCartRepository as StripeCart  $stripeCart
     */
    public function __construct(
        protected stripeRepository $stripeRepository,
        protected StripeCart $stripeCart
    ) {}

    /**
     * Separate seller according to their product
     *
     * @return array
     */
    public function productDetail()
    {
        return null;
    }

    /**
     * Create payment for stripe
     *
     * @param  string  $payment
     * @param  string  $stripeId
     * @param  string  $paymentMethodId
     * @param  string  $customerId
     * @return object
     */
    public function stripePayment($payment = '', $stripeId = '', $paymentMethodId = '', $customerId = '')
    {
        try {
            if ($customerId != '') {
                $result = PaymentIntent::create([
                    'amount'                => round(Cart::getCart()->base_grand_total, 2) * 100,
                    'customer'              => $customerId,
                    'currency'              => core()->getBaseCurrencyCode(),
                    'payment_method_types'  => ['card'],
                    'receipt_email'         => Cart::getCart()->customer_email,
                    'description'           => trans('stripe::app.payment-for-order-id').' - #'.Cart::getCart()->id,
                    'shipping'              => [
                        'name'              => Cart::getCart()->customer_first_name.' '.Cart::getCart()->customer_last_name,
                        'phone'             => Cart::getCart()->billing_address->phone,
                        'address'           => [
                            'city'          => Cart::getCart()->billing_address->city,
                            'country'       => Cart::getCart()->billing_address->country,
                            'line1'         => Cart::getCart()->billing_address->address1,
                            'line2'         => Cart::getCart()->billing_address->address2,
                            'postal_code'   => Cart::getCart()->billing_address->postcode,
                            'state'         => Cart::getCart()->billing_address->state,
                        ],
                    ],
                    'metadata' => [
                        'order_id' => Cart::getCart()->id,
                    ],
                ]);

                $response = [
                    'paymentIntent' => $result,
                ];

                $this->stripeCart->create([
                    'cart_id'       => Cart::getCart()->id,
                    'stripe_token'  => json_encode($response),
                ]);
            } else {
                $result = PaymentIntent::create([
                    'amount'                => round(Cart::getCart()->base_grand_total, 2) * 100,
                    'currency'              => core()->getBaseCurrencyCode(),
                    'payment_method_types'  => ['card'],
                    'receipt_email'         => Cart::getCart()->customer_email,
                    'description'           => trans('stripe::app.payment-for-order-id').' - #'.Cart::getCart()->id,
                    'shipping'              => [
                        'name'              => Cart::getCart()->customer_first_name.' '.Cart::getCart()->customer_last_name,
                        'phone'             => Cart::getCart()->billing_address->phone,
                        'address'           => [
                            'city'          => Cart::getCart()->billing_address->city,
                            'country'       => Cart::getCart()->billing_address->cpuntry,
                            'line1'         => Cart::getCart()->billing_address->address1,
                            'line2'         => Cart::getCart()->billing_address->address2,
                            'postal_code'   => Cart::getCart()->billing_address->postcode,
                            'state'         => Cart::getCart()->billing_address->state,
                        ],
                    ],
                    'metadata' => [
                        'order_id' => Cart::getCart()->id,
                    ],
                ]);

                $stripeToken = $this->stripeCart->scopeQuery(function ($query) {
                    return $query->orderBy('id', 'desc');
                })->findByField('cart_id', Cart::getCart()->id)->first()->stripe_token;

                $decodeStripeToken = json_decode($stripeToken);

                $response = [
                    'customerResponse'  => $decodeStripeToken->customerResponse,
                    'attachedCustomer'  => $decodeStripeToken->attachedCustomer,
                    'paymentIntent'     => $result,
                ];

                $stripeToken = $this->stripeCart->scopeQuery(function ($query) {
                    return $query->orderBy('id', 'desc');
                })->findByField('cart_id', Cart::getCart()->id)->first()->update([
                    'stripe_token' => json_encode($response),
                ]);
            }
        } catch (\Exception$e) {
            return $e->getMessage();
        }

        return $result;
    }

    /**
     * Delete card if payment not done
     *
     * @param  object  $getCartDecode
     * @return void
     */
    public function deleteCardIfPaymentNotDone($getCartDecode)
    {
        if (! empty($getCartDecode->stripeReturn->last4)) {
            $this->stripeRepository->deleteWhere([
                'last_four' => $getCartDecode->stripeReturn->last4,
            ]);
        }
    }
}
