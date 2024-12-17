<?php

namespace Webkul\Tax;

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
     * Is product prices are tax inclusive.
     */
    public function isInclusiveTaxProductPrices(): bool
    {
        return core()->getConfigData('sales.taxes.calculation.product_prices') == 'including_tax';
    }

    /**
     * Is shipping prices are tax inclusive.
     */
    public function isInclusiveTaxShippingPrices(): bool
    {
        return core()->getConfigData('sales.taxes.calculation.shipping_prices') == 'including_tax';
    }

    /**
     * Returns an array with tax rates and tax amount.
     */
    public function getTaxRatesWithAmount(object $that, bool $asBase = false): array
    {
        $taxes = [];

        foreach ($that->items as $item) {
            $taxRate = $item->applied_tax_rate.' ('.(string) round((float) $item->tax_percent, self::TAX_RATE_PRECISION).'%)';

            if (! array_key_exists($taxRate, $taxes)) {
                $taxes[$taxRate] = 0;
            }

            $taxes[$taxRate] += $asBase ? $item->base_tax_amount : $item->tax_amount;
        }

        if (
            $that->selected_shipping_rate
            && $that->selected_shipping_rate->tax_amount > 0
        ) {
            $taxRate = $that->selected_shipping_rate->applied_tax_rate.' ('.(string) round((float) $that->selected_shipping_rate->tax_percent, self::TAX_RATE_PRECISION).'%)';

            if (! array_key_exists($taxRate, $taxes)) {
                $taxes[$taxRate] = 0;
            }

            $taxes[$taxRate] += $asBase ? $that->selected_shipping_rate->base_tax_amount : $that->selected_shipping_rate->tax_amount;
        }

        /**
         * Finally round tax amounts now (to reduce rounding differences)
         */
        foreach ($taxes as $taxRate => $taxAmount) {
            $taxes[$taxRate] = round($taxAmount, self::TAX_AMOUNT_PRECISION);
        }

        return $taxes;
    }

    /**
     * Get shipping origin from core config.
     */
    public function getShippingOriginAddress(): object
    {
        return new class
        {
            public $country;

            public $state;

            public $postcode;

            public function __construct()
            {
                $this->country = core()->getConfigData('sales.shipping.origin.country') != ''
                    ? core()->getConfigData('sales.shipping.origin.country')
                    : strtoupper(config('app.default_country'));

                $this->state = core()->getConfigData('sales.shipping.origin.state');

                $this->postcode = core()->getConfigData('sales.shipping.origin.postcode');
            }
        };
    }

    /**
     * Get default address from core config.
     */
    public function getDefaultAddress(): object
    {
        return new class
        {
            public $country;

            public $state;

            public $postcode;

            public function __construct()
            {
                $this->country = core()->getConfigData('sales.taxes.default_destination_calculation.country') != ''
                    ? core()->getConfigData('sales.taxes.default_destination_calculation.country')
                    : strtoupper(config('app.default_country'));

                $this->state = core()->getConfigData('sales.taxes.default_destination_calculation.state');

                $this->postcode = core()->getConfigData('sales.taxes.default_destination_calculation.post_code');
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
     */
    public function isTaxApplicableInCurrentAddress($taxCategory, $address, $operation): void
    {
        if (! $address?->country) {
            return;
        }

        $taxRates = $taxCategory->tax_rates()->where([
            'country' => $address->country,
        ])->orderBy('tax_rate', 'desc')->get();

        if (! $taxRates->count()) {
            return;
        }

        // dump($address);
        foreach ($taxRates as $rate) {
            if (
                ! in_array(trim($rate->state), ['*', ''])
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
