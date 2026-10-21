<?php

namespace Webkul\Checkout\Helpers;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Contracts;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Tax\Facades\Tax;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class CartTax
{
    /**
     * Tax is calculated against the address the store ships from.
     */
    const BASED_ON_SHIPPING_ORIGIN = 'shipping_origin';

    /**
     * Tax is calculated against the cart's billing address.
     */
    const BASED_ON_BILLING_ADDRESS = 'billing_address';

    /**
     * Tax is calculated against the cart's shipping address.
     */
    const BASED_ON_SHIPPING_ADDRESS = 'shipping_address';

    /**
     * Create a new helper instance.
     */
    public function __construct(
        protected TaxCategoryRepository $taxCategoryRepository,
        protected CustomerAddressRepository $customerAddressRepository
    ) {}

    /**
     * Break the tax out of, or add it on top of, every item the cart holds.
     */
    public function calculateItemsTax(Contracts\Cart $cart): void
    {
        Event::dispatch('checkout.cart.calculate.items.tax.before', $cart);

        $taxCategories = [];

        foreach ($cart->items as $key => $item) {
            $taxCategoryId = $this->itemTaxCategoryId($item);

            if (! $taxCategoryId) {
                continue;
            }

            if (! isset($taxCategories[$taxCategoryId])) {
                $taxCategories[$taxCategoryId] = $this->taxCategoryRepository->find($taxCategoryId);
            }

            if (! $taxCategories[$taxCategoryId]) {
                continue;
            }

            $address = $this->itemTaxAddress($cart, $item);

            $item->applied_tax_rate = null;

            $item->tax_percent = $item->tax_amount = $item->base_tax_amount = 0;

            Tax::isTaxApplicableInCurrentAddress($taxCategories[$taxCategoryId], $address, function ($rate) use ($item, $taxCategoryId) {
                $item->applied_tax_rate = $rate->identifier;

                $item->tax_category_id = $taxCategoryId;

                $item->tax_percent = $rate->tax_rate;

                if (Tax::isInclusiveTaxProductPrices()) {
                    $this->applyInclusiveItemTax($item, $rate);
                } else {
                    $this->applyExclusiveItemTax($item, $rate);
                }
            });

            if (empty($item->applied_tax_rate)) {
                $item->price_incl_tax = $item->price;
                $item->base_price_incl_tax = $item->base_price;

                $item->total_incl_tax = $item->total;
                $item->base_total_incl_tax = $item->base_total;
            }

            $item->save();

            $cart->items->put($key, $item);
        }

        Event::dispatch('checkout.cart.calculate.items.tax.after', $cart);
    }

    /**
     * Break the tax out of, or add it on top of, the shipping rate the shopper chose.
     */
    public function calculateShippingTax(Contracts\Cart $cart): void
    {
        $shippingRate = $cart->selected_shipping_rate;

        if (! $shippingRate) {
            return;
        }

        if (! $taxCategoryId = core()->getConfigData('sales.taxes.categories.shipping')) {
            return;
        }

        $taxCategory = $this->taxCategoryRepository->find($taxCategoryId);

        if (! $taxCategory) {
            return;
        }

        $address = $this->shippingTaxAddress($cart);

        Event::dispatch('checkout.cart.calculate.shipping.tax.before', $cart);

        Tax::isTaxApplicableInCurrentAddress($taxCategory, $address, function ($rate) use ($shippingRate) {
            $shippingRate->applied_tax_rate = $rate->identifier;

            $shippingRate->tax_percent = $rate->tax_rate;

            if (Tax::isInclusiveTaxShippingPrices()) {
                $this->applyInclusiveShippingTax($shippingRate, $rate);
            } else {
                $this->applyExclusiveShippingTax($shippingRate, $rate);
            }
        });

        if (empty($shippingRate->applied_tax_rate)) {
            $shippingRate->price_incl_tax = $shippingRate->price;

            $shippingRate->base_price_incl_tax = $shippingRate->base_price;
        }

        $shippingRate->save();

        Event::dispatch('checkout.cart.calculate.shipping.tax.after', $cart);
    }

    /**
     * The tax category an item is taxed under, from the item, then its product, then the store.
     */
    protected function itemTaxCategoryId(Contracts\CartItem $item): ?int
    {
        $taxCategoryId = $item->tax_category_id
            ?: $item->product->tax_category_id
            ?: core()->getConfigData('sales.taxes.categories.product');

        return $taxCategoryId ? (int) $taxCategoryId : null;
    }

    /**
     * Break the tax out of an item's gross total without taking the discount off the taxable base,
     * because an inclusive price is what the customer pays whatever the apply-tax-on setting says.
     */
    protected function applyInclusiveItemTax(Contracts\CartItem $item, $rate): void
    {
        $item->tax_amount = round(($item->total_incl_tax * $rate->tax_rate) / (100 + $rate->tax_rate), 4);

        $item->base_tax_amount = round(($item->base_total_incl_tax * $rate->tax_rate) / (100 + $rate->tax_rate), 4);

        $item->total = $item->total_incl_tax - $item->tax_amount;

        $item->base_total = $item->base_total_incl_tax - $item->base_tax_amount;

        $item->price = $item->total / $item->quantity;

        $item->base_price = $item->base_total / $item->quantity;
    }

    /**
     * Add tax on top of an item's net total.
     */
    protected function applyExclusiveItemTax(Contracts\CartItem $item, $rate): void
    {
        $taxableTotal = $item->total;

        $baseTaxableTotal = $item->base_total;

        if ($this->isTaxAppliedAfterDiscount()) {
            $taxableTotal -= $item->discount_amount;

            $baseTaxableTotal -= $item->base_discount_amount;
        }

        $item->tax_amount = round(($taxableTotal * $rate->tax_rate) / 100, 4);

        $item->base_tax_amount = round(($baseTaxableTotal * $rate->tax_rate) / 100, 4);

        $item->total_incl_tax = $item->total + $item->tax_amount;

        $item->base_total_incl_tax = $item->base_total + $item->base_tax_amount;

        $item->price_incl_tax = $item->price + ($item->tax_amount / $item->quantity);

        $item->base_price_incl_tax = $item->base_price + ($item->base_tax_amount / $item->quantity);
    }

    /**
     * Break the tax out of a shipping rate's gross price without taking the discount off the
     * taxable base, for the same reason an inclusive item price does not.
     */
    protected function applyInclusiveShippingTax(Contracts\CartShippingRate $shippingRate, $rate): void
    {
        $shippingRate->tax_amount = round(($shippingRate->price_incl_tax * $rate->tax_rate) / (100 + $rate->tax_rate), 4);

        $shippingRate->base_tax_amount = round(($shippingRate->base_price_incl_tax * $rate->tax_rate) / (100 + $rate->tax_rate), 4);

        $shippingRate->price = $shippingRate->price_incl_tax - $shippingRate->tax_amount;

        $shippingRate->base_price = $shippingRate->base_price_incl_tax - $shippingRate->base_tax_amount;
    }

    /**
     * Add tax on top of a shipping rate's net price.
     */
    protected function applyExclusiveShippingTax(Contracts\CartShippingRate $shippingRate, $rate): void
    {
        $taxablePrice = $shippingRate->price;

        $baseTaxablePrice = $shippingRate->base_price;

        if ($this->isTaxAppliedAfterDiscount()) {
            $taxablePrice -= $shippingRate->discount_amount;

            $baseTaxablePrice -= $shippingRate->base_discount_amount;
        }

        $shippingRate->tax_amount = round(($taxablePrice * $rate->tax_rate) / 100, 4);

        $shippingRate->base_tax_amount = round(($baseTaxablePrice * $rate->tax_rate) / 100, 4);

        $shippingRate->price_incl_tax = $shippingRate->price + $shippingRate->tax_amount;

        $shippingRate->base_price_incl_tax = $shippingRate->base_price + $shippingRate->base_tax_amount;
    }

    /**
     * The address an item's tax is calculated against, which for a non-stockable item is the
     * billing address even where the store taxes on the shipping address.
     */
    protected function itemTaxAddress(Contracts\Cart $cart, Contracts\CartItem $item)
    {
        $calculationBasedOn = core()->getConfigData('sales.taxes.calculation.based_on');

        $address = null;

        if ($calculationBasedOn == self::BASED_ON_SHIPPING_ORIGIN) {
            $address = Tax::getShippingOriginAddress();
        } elseif ($calculationBasedOn == self::BASED_ON_SHIPPING_ADDRESS) {
            if ($item->getTypeInstance()->isStockable()) {
                $address = $cart->shipping_address;
            } else {
                $address = $cart->billing_address;
            }
        } elseif ($calculationBasedOn == self::BASED_ON_BILLING_ADDRESS) {
            $address = $cart->billing_address;
        }

        return $this->fallbackTaxAddress($cart, $address);
    }

    /**
     * The address the shipping tax is calculated against, which a cart carrying nothing stockable
     * leaves to the fallback rather than the billing address.
     */
    protected function shippingTaxAddress(Contracts\Cart $cart)
    {
        $calculationBasedOn = core()->getConfigData('sales.taxes.calculation.based_on');

        $address = null;

        if ($calculationBasedOn == self::BASED_ON_SHIPPING_ORIGIN) {
            $address = Tax::getShippingOriginAddress();
        } elseif (
            $cart->haveStockableItems()
            && $calculationBasedOn == self::BASED_ON_SHIPPING_ADDRESS
        ) {
            $address = $cart->shipping_address;
        } elseif ($calculationBasedOn == self::BASED_ON_BILLING_ADDRESS) {
            $address = $cart->billing_address;
        }

        return $this->fallbackTaxAddress($cart, $address);
    }

    /**
     * The customer's default address, then the store's own, for a tax the configured mode left
     * without one.
     */
    protected function fallbackTaxAddress(Contracts\Cart $cart, $address)
    {
        if (
            $address === null
            && $cart->customer_id
        ) {
            $address = $this->customerAddressRepository->findOneWhere([
                'customer_id' => $cart->customer_id,
                'default_address' => 1,
            ]);
        }

        if ($address === null) {
            $address = Tax::getDefaultAddress();
        }

        return $address;
    }

    /**
     * Whether the store takes the discount off the taxable base, as a VAT jurisdiction requires.
     */
    protected function isTaxAppliedAfterDiscount(): bool
    {
        return core()->getConfigData('sales.taxes.calculation.apply_tax_on') == 'after_discount';
    }
}
