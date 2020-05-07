<?php

namespace Webkul\Tax\Helpers;

class Tax
{
    /**
     * @var int
     */
    private const TAX_RATE_PRECISION = 4;
    private const TAX_AMOUNT_PRECISION = 2;

    /**
     * Returns an array with tax rates and tax amount
     *
     * @param \Webkul\Checkout\Contracts\Cart $cart
     * @param bool                            $asBase
     *
     * @return array
     */
    public static function getTaxRatesWithAmount(\Webkul\Checkout\Contracts\Cart $cart, bool $asBase = false): array
    {
        $taxes = [];

        foreach ($cart->items as $item) {
            $taxRate = (string) round((float) $item->tax_percent, self::TAX_RATE_PRECISION);

            if (! array_key_exists($taxRate, $taxes)) {
                $taxes[$taxRate] = 0;
            }

            $taxes[$taxRate] += $asBase ? $item->base_tax_amount : $item->tax_amount;
        }

        // finally round tax amounts now (to reduce rounding differences)
        foreach ($taxes as $taxRate => $taxAmount) {
            $taxes[$taxRate] = round($taxAmount, self::TAX_AMOUNT_PRECISION);
        }

        return $taxes;
    }

    /**
     * Returns the total tax amount
     *
     * @param \Webkul\Checkout\Contracts\Cart $cart
     * @param bool                            $asBase
     *
     * @return float
     */
    public static function getTaxTotal(\Webkul\Checkout\Contracts\Cart $cart, bool $asBase = false): float
    {
        $taxes = self::getTaxRatesWithAmount($cart, $asBase);

        $result = 0;

        foreach ($taxes as $taxRate => $taxAmount) {
            $result += $taxAmount;
        }

        return $result;
    }
}