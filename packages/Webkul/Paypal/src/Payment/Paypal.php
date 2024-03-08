<?php

namespace Webkul\Paypal\Payment;

use Illuminate\Support\Facades\Storage;
use Webkul\Payment\Payment\Payment;

abstract class Paypal extends Payment
{
    /**
     * PayPal web URL generic getter
     *
     * @param  array  $params
     * @return string
     */
    public function getPaypalUrl($params = [])
    {
        return sprintf('https://www.%spaypal.com/cgi-bin/webscr%s',
            $this->getConfigData('sandbox') ? 'sandbox.' : '',
            $params ? '?'.http_build_query($params) : ''
        );
    }

    /**
     * Add order item fields
     *
     * @param  array  $fields
     * @param  int  $i
     * @return void
     */
    protected function addLineItemsFields(&$fields, $i = 1)
    {
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $item) {
            foreach ($this->itemFieldsFormat as $modelField => $paypalField) {
                $fields[sprintf($paypalField, $i)] = $item->{$modelField};
            }

            $i++;
        }
    }

    /**
     * Add billing address fields
     *
     * @param  array  $fields
     * @return void
     */
    protected function addAddressFields(&$fields)
    {
        $cart = $this->getCart();

        $billingAddress = $cart->billing_address;

        $fields = array_merge($fields, [
            'city'             => $billingAddress->city,
            'country'          => $billingAddress->country,
            'email'            => $billingAddress->email,
            'first_name'       => $billingAddress->first_name,
            'last_name'        => $billingAddress->last_name,
            'zip'              => $billingAddress->postcode,
            'state'            => $billingAddress->state,
            'address'          => $billingAddress->address,
            'address_override' => 1,
        ]);
    }

    /**
     * Checks if line items enabled or not
     *
     * @return bool
     */
    public function getIsLineItemsEnabled()
    {
        return true;
    }

    /**
     * Format a currency value according to paypal's api constraints
     *
     * @param  float|int  $long
     */
    public function formatCurrencyValue($number): float
    {
        return round((float) $number, 2);
    }

    /**
     * Format phone field according to paypal's api constraints
     *
     * Strips non-numbers characters like '+' or ' ' in
     * inputs like "+54 11 3323 2323"
     *
     * @param  mixed  $phone
     */
    public function formatPhone($phone): string
    {
        return preg_replace('/[^0-9]/', '', (string) $phone);
    }

    /**
     * Returns payment method image
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/paypal.png', 'shop');
    }
}
