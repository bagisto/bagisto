<?php

namespace Webkul\Tax\Helpers;

class Tax
{
    private const TAX_PRECISION = 4;

    /**
     * Returns an array with tax rates and tax amount
     * @param object $that
     * @param bool   $asBase
     *
     * @return array
     */
    public static function getTaxRatesWithAmount(object $that, bool $asBase = false): array
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

    /**
     * Returns the total tax amount
     * @param object $that
     * @param bool   $asBase
     *
     * @return float
     */
    public static function getTaxTotal(object $that, bool $asBase = false): float
    {
        $taxes = self::getTaxRatesWithAmount($that, $asBase);

        $result = 0;
        foreach ($taxes as $taxRate => $taxAmount) {
            $result += round($taxAmount, 2);
        }
        return $result;
    }
}