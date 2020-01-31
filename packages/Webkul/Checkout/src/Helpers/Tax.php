<?php

namespace Webkul\Checkout\Helpers;

class Tax
{
    private const TAX_PRECISION = 4;

    /**
     * Returns an array with tax rates and tax amounts
     * @param object $that
     * @param bool   $asBase
     *
     * @return array
     */
    public static function getTaxRatesWithAmount(object $that, bool $asBase): array
    {
        $taxes = [];
        foreach ($that->items as $item) {
            $taxRate = (string)round((float)$item->tax_percent, self::TAX_PRECISION);

            if (array_key_exists($taxRate, $taxes)) {
                $taxes[$taxRate] += $asBase ? $item->base_tax_amount : $item->tax_amount;
            } else {
                $taxes += [$taxRate => $asBase ? $item->base_tax_amount : $item->tax_amount];
            }
        }

        return $taxes;
    }
}