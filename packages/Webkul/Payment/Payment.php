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
            if($paymentMethod['active']) {
                $object = new $paymentMethod['class'];

                $paymentMethods[] = [
                    'code' => $object->getCode(),
                    'title' => $object->getTitle(),
                    'description' => $object->getDescription(),
                ];
            }
        }

        return $paymentMethods;
    }
}