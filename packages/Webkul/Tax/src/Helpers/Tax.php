<?php

namespace Webkul\Tax\Helpers;

/**
 * Tax class.
 *
 * To Do (@devansh-webkul): Convert this to facade.
 */
class Tax
{
    /**
     * Tax rate precision.
     *
     * @var int
     */
    private const TAX_RATE_PRECISION = 4;

    /**
     * Tax amount precision.
     *
     * @var int
     */
    private const TAX_AMOUNT_PRECISION = 2;

    /**
     * Is tax inclusive enabled in backend.
     */
    public static function isTaxInclusive(): bool
    {
        return (bool) core()->getConfigData('taxes.catalogue.pricing.tax_inclusive');
    }

    /**
     * Returns an array with tax rates and tax amount.
     */
    public static function getTaxRatesWithAmount(object $that, bool $asBase = false): array
    {
        $taxes = [];

        foreach ($that->items as $item) {
            $taxRate = (string) round((float) $item->tax_percent, self::TAX_RATE_PRECISION);

            if (! array_key_exists($taxRate, $taxes)) {
                $taxes[$taxRate] = 0;
            }

            $taxes[$taxRate] += $asBase ? $item->base_tax_amount : $item->tax_amount;
        }

        /* finally round tax amounts now (to reduce rounding differences) */
        foreach ($taxes as $taxRate => $taxAmount) {
            $taxes[$taxRate] = round($taxAmount, self::TAX_AMOUNT_PRECISION);
        }

        return $taxes;
    }

    /**
     * Returns the total tax amount.
     */
    public static function getTaxTotal(object $that, bool $asBase = false): float
    {
        $taxes = self::getTaxRatesWithAmount($that, $asBase);

        $result = 0;

        foreach ($taxes as $taxRate => $taxAmount) {
            $result += $taxAmount;
        }

        return $result;
    }

    /**
     * Get default address from core config.
     *
     * @return object
     */
    public static function getDefaultAddress()
    {
        return new class()
        {
            public $country;

            public $state;

            public $postcode;

            public function __construct()
            {
                $this->country = core()->getConfigData('taxes.catalogue.default_location_calculation.country') != ''
                    ? core()->getConfigData('taxes.catalogue.default_location_calculation.country')
                    : strtoupper(config('app.default_country'));

                $this->state = core()->getConfigData('taxes.catalogue.default_location_calculation.state');

                $this->postcode = core()->getConfigData('taxes.catalogue.default_location_calculation.post_code');
            }
        };
    }

    /**
     * This method will check tax for the current address. If applicable then
     * custom operation can be done.
     *
     * @param  object  $address
     * @param  object  $taxCategory
     * @param  \Closure  $operation
     * @return void
     */
    public static function isTaxApplicableInCurrentAddress($taxCategory, $address, $operation)
    {
        $taxRates = $taxCategory->tax_rates()->where([
            'country' => $address?->country,
        ])->orderBy('tax_rate', 'desc')->get();

        if (! $taxRates->count()) {
            return;
        }

        foreach ($taxRates as $rate) {
            if (
                $rate->state != ''
                && $rate->state != $address->state
            ) {
                continue;
            }

            $haveTaxRate = false;

            if (! $rate->is_zip) {
                if (
                    empty($rate->zip_code)
                    || in_array($rate->zip_code, ['*', $address->postcode])
                ) {
                    $haveTaxRate = true;
                }
            } else {
                if (
                    $address->postcode >= $rate->zip_from
                    && $address->postcode <= $rate->zip_to
                ) {
                    $haveTaxRate = true;
                }
            }

            if ($haveTaxRate) {
                $operation($rate);

                break;
            }
        }
    }
}
