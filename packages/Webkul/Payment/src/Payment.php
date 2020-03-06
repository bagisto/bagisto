<?php

namespace Webkul\Payment;

use Illuminate\Support\Facades\Config;

class Payment
{

    /**
     * Returns all supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods()
    {
        $paymentMethods = $this->getPaymentMethods();

        return [
            'jump_to_section' => 'payment',
            'paymentMethods'  => $this->getPaymentMethods(),
            'html'            => view('shop::checkout.onepage.payment', compact('paymentMethods'))->render(),
        ];
    }

    /**
     * Returns all supported payment methods
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = [];

        foreach (Config::get('paymentmethods') as $paymentMethod) {
            $object = app($paymentMethod['class']);

            if ($object->isAvailable()) {
                $paymentMethods[] = [
                    'method'       => $object->getCode(),
                    'method_title' => $object->getTitle(),
                    'description'  => $object->getDescription(),
                    'sort'         => $object->getSortOrder(),
                ];
            }
        }

        usort ($paymentMethods, function($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }

            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });

        return $paymentMethods;
    }

    /**
     * Returns payment redirect url if have any
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return string
     */
    public function getRedirectUrl($cart)
    {
        $payment = app(Config::get('paymentmethods.' . $cart->payment->method . '.class'));

        return $payment->getRedirectUrl();
    }
}