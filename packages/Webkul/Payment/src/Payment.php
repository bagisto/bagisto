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
        $paymentMethods = [];

        foreach (Config::get('paymentmethods') as $paymentMethod) {
                $object = new $paymentMethod['class'];

                if($object->isAvailable()) {
                    $paymentMethods[] = [
                        'method' => $object->getCode(),
                        'method_title' => $object->getTitle(),
                        'description' => $object->getDescription(),
                    ];
                }
        }

        return [
                'jump_to_section' => 'payment',
                'html' => view('shop::checkout.onepage.payment', compact('paymentMethods'))->render()
            ];
    }
}