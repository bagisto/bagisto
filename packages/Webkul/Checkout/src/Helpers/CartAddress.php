<?php

namespace Webkul\Checkout\Helpers;

use Webkul\Checkout\Contracts;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;
use Webkul\Checkout\Exceptions\BillingAddressNotFoundException;
use Webkul\Checkout\Models\CartAddress as CartAddressModel;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;

class CartAddress
{
    /**
     * The request fields a billing address is built from.
     */
    const BILLING_FIELDS = [
        'use_for_shipping',
        'default_address',
        'company_name',
        'first_name',
        'last_name',
        'vat_id',
        'email',
        'address',
        'country',
        'state',
        'city',
        'postcode',
        'phone',
    ];

    /**
     * The fields a shipping address is built from, whether copied from billing or posted.
     */
    const SHIPPING_FIELDS = [
        'default_address',
        'company_name',
        'first_name',
        'last_name',
        'email',
        'address',
        'country',
        'state',
        'city',
        'postcode',
        'phone',
    ];

    /**
     * Create a new helper instance.
     */
    public function __construct(
        protected CartAddressRepository $cartAddressRepository,
        protected CustomerAddressRepository $customerAddressRepository
    ) {}

    /**
     * Write the cart's billing address and then its shipping address, in that order because the
     * shipping side reads the billing address this leaves on the cart.
     */
    public function save(Contracts\Cart $cart, array $params): void
    {
        $this->updateOrCreateBilling($cart, $params['billing']);

        $this->updateOrCreateShipping($cart, $params['shipping'] ?? []);
    }

    /**
     * The saved address a cart address was filled from, when it belongs to this cart's customer.
     */
    public function customerAddressId(Contracts\Cart $cart, array $params): ?int
    {
        if (
            ($params['address_type'] ?? '') !== 'customer'
            || empty($params['id'])
            || ! $cart->customer_id
        ) {
            return null;
        }

        $address = $this->customerAddressRepository->find($params['id']);

        return $address?->customer_id === $cart->customer_id
            ? $address->id
            : null;
    }

    /**
     * Update or create billing address.
     */
    public function updateOrCreateBilling(Contracts\Cart $cart, array $params): CartAddressContract
    {
        $params = collect($params)
            ->only(self::BILLING_FIELDS)
            ->merge([
                'address_type' => CartAddressModel::ADDRESS_TYPE_BILLING,
                'parent_address_id' => $this->customerAddressId($cart, $params),
                'cart_id' => $cart->id,
                'customer_id' => $cart->customer_id,
                'address' => implode(PHP_EOL, $params['address']),
                'use_for_shipping' => (bool) ($params['use_for_shipping'] ?? false),
            ])
            ->toArray();

        if ($cart->billing_address) {
            $address = $this->cartAddressRepository->update($params, $cart->billing_address->id);
        } else {
            $address = $this->cartAddressRepository->create($params);
        }

        $cart->setRelation('billing_address', $address);

        return $address;
    }

    /**
     * Update or create shipping address.
     */
    public function updateOrCreateShipping(Contracts\Cart $cart, array $params): ?CartAddressContract
    {
        if (! $cart->haveStockableItems()) {
            return null;
        }

        if (! $cart->billing_address) {
            throw new BillingAddressNotFoundException;
        }

        if ($cart->billing_address->use_for_shipping) {
            $params = $this->shippingParamsFromBilling($cart);
        } else {
            $params = $this->shippingParamsFromRequest($cart, $params);

            if ($params === null) {
                return null;
            }
        }

        if ($cart->shipping_address) {
            $address = $this->cartAddressRepository->update($params, $cart->shipping_address->id);
        } else {
            $params['default_address'] = 0;

            $address = $this->cartAddressRepository->create($params);
        }

        $cart->setRelation('shipping_address', $address);

        return $address;
    }

    /**
     * A shipping address copied off the billing address, whose `address` is already a joined string.
     */
    protected function shippingParamsFromBilling(Contracts\Cart $cart): array
    {
        return array_merge($cart->billing_address->only(self::SHIPPING_FIELDS), [
            'address_type' => CartAddressModel::ADDRESS_TYPE_SHIPPING,
            'parent_address_id' => $cart->billing_address->parent_address_id,
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
        ]);
    }

    /**
     * A shipping address built from posted fields, whose `address` arrives as lines to join, or
     * nothing when none were posted.
     */
    protected function shippingParamsFromRequest(Contracts\Cart $cart, array $params): ?array
    {
        if (empty($params)) {
            return null;
        }

        return collect($params)
            ->only(self::SHIPPING_FIELDS)
            ->merge([
                'address_type' => CartAddressModel::ADDRESS_TYPE_SHIPPING,
                'parent_address_id' => $this->customerAddressId($cart, $params),
                'cart_id' => $cart->id,
                'customer_id' => $cart->customer_id,
                'address' => implode(PHP_EOL, $params['address']),
            ])
            ->toArray();
    }
}
