<?php

namespace Webkul\Checkout;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;
use Webkul\Checkout\Helpers\CartAddress;
use Webkul\Checkout\Helpers\CartTax;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Contracts\Wishlist as WishlistContract;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Contracts\Product as ProductContract;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Tax\Facades\Tax;

class Cart
{
    /**
     * Constant for tax calculation based on shipping origin.
     */
    const TAX_CALCULATION_BASED_ON_SHIPPING_ORIGIN = CartTax::BASED_ON_SHIPPING_ORIGIN;

    /**
     * Constant for tax calculation based on billing address.
     */
    const TAX_CALCULATION_BASED_ON_BILLING_ADDRESS = CartTax::BASED_ON_BILLING_ADDRESS;

    /**
     * Constant for tax calculation based on shipping address.
     */
    const TAX_CALCULATION_BASED_ON_SHIPPING_ADDRESS = CartTax::BASED_ON_SHIPPING_ADDRESS;

    /**
     * The request fields a billing address is built from.
     */
    const BILLING_ADDRESS_FIELDS = CartAddress::BILLING_FIELDS;

    /**
     * The fields a shipping address is built from, whether copied from billing or posted.
     */
    const SHIPPING_ADDRESS_FIELDS = CartAddress::SHIPPING_FIELDS;

    /**
     * The cart instance.
     *
     * @var Contracts\Cart
     */
    private $cart;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected CartAddress $cartAddress,
        protected CartTax $cartTax,
        protected WishlistRepository $wishlistRepository
    ) {
        $this->initCart();
    }

    /**
     * Initialize cart.
     */
    public function initCart(?CustomerContract $customer = null): void
    {
        if (! $customer) {
            $customer = auth()->guard()->user();
        }

        if ($customer) {
            $this->cart = $this->cartRepository->findOneWhere([
                'customer_id' => $customer->id,
                'is_active' => 1,
            ]);
        } elseif (session()->has('cart')) {
            $cart = $this->cartRepository->find(session()->get('cart')->id);

            if (
                $cart
                && $cart->is_active
            ) {
                $this->cart = $cart;
            }
        }
    }

    /**
     * Reload the cart from the database.
     */
    public function refreshCart(): void
    {
        if (! $this->cart) {
            return;
        }

        $this->cart = $this->cartRepository->find($this->cart->id);
    }

    /**
     * Set cart.
     */
    public function setCart(Contracts\Cart $cart): void
    {
        $this->cart = $cart;

        if ($this->cart->customer) {
            return;
        }

        $cartTemp = new \stdClass;
        $cartTemp->id = $this->cart->id;

        session()->put('cart', $cartTemp);
    }

    /**
     * Returns cart.
     */
    public function getCart(): ?Contracts\Cart
    {
        return $this->cart;
    }

    /**
     * Create new cart instance.
     */
    public function createCart(array $data): ?Contracts\Cart
    {
        $data = array_merge([
            'is_guest' => 1,
            'channel_id' => core()->getCurrentChannel()->id,
            'global_currency_code' => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code' => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code' => core()->getCurrentCurrencyCode(),
        ], $data);

        $customer = $data['customer'] ?? auth()->guard()->user();

        if ($customer) {
            $data = array_merge($data, [
                'is_guest' => 0,
                'customer_id' => $customer->id,
                'customer_first_name' => $customer->first_name,
                'customer_last_name' => $customer->last_name,
                'customer_email' => $customer->email,
            ]);
        }

        $cart = $this->cartRepository->create($data);

        $this->setCart($cart);

        return $cart;
    }

    /**
     * Remove cart and destroy the session.
     */
    public function removeCart(Contracts\Cart $cart): void
    {
        $this->cartRepository->delete($cart->id);

        if (session()->has('cart')) {
            session()->forget('cart');
        }

        $this->resetCart();
    }

    /**
     * Reset cart.
     */
    public function resetCart(): void
    {
        $this->cart = null;
    }

    /**
     * Activate the cart by id.
     */
    public function activateCart(int $cartId): void
    {
        $cart = $this->cartRepository->update([
            'is_active' => true,
        ], $cartId);

        $this->setCart($cart);
    }

    /**
     * Deactivates current cart.
     */
    public function deActivateCart(): void
    {
        if (! $this->cart) {
            return;
        }

        $this->cartRepository->update(['is_active' => false], $this->cart->id);

        $this->resetCart();

        if (session()->has('cart')) {
            session()->forget('cart');
        }
    }

    /**
     * This method handles when guest has some of cart products and then logs in.
     */
    public function mergeCart(CustomerContract $customer): void
    {
        if (! session()->has('cart')) {
            return;
        }

        $cart = $this->cartRepository->findOneWhere([
            'customer_id' => $customer->id,
            'is_active' => 1,
        ]);

        $guestCart = $this->cartRepository->find(session()->get('cart')->id);

        if (! $cart) {
            $this->cartRepository->update([
                'customer_id' => $customer->id,
                'is_guest' => 0,
                'customer_first_name' => $customer->first_name,
                'customer_last_name' => $customer->last_name,
                'customer_email' => $customer->email,
            ], $guestCart->id);

            session()->forget('cart');

            return;
        }

        $this->setCart($cart);

        foreach ($guestCart->items as $guestCartItem) {
            try {
                $this->addProduct($guestCartItem->product, $guestCartItem->additional);
            } catch (\Exception $e) {
            }
        }

        $this->collectTotals();

        $this->removeCart($guestCart);
    }

    /**
     * Add items in a cart with some cart and item details.
     */
    public function addProduct(ProductContract $product, array $data): Contracts\Cart
    {
        if (! $product->isAvailableInChannel($this->cart?->channel_id)) {
            throw new \Exception(trans('shop::app.checkout.cart.inactive-add'));
        }

        Event::dispatch('checkout.cart.add.before', $product->id);

        if (! $this->cart) {
            $this->createCart([]);
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart(array_merge($data, [
            'cart_id' => $this->cart->id,
        ]));

        if (is_string($cartProducts)) {
            if (! $this->cart->all_items->count()) {
                $this->removeCart($this->cart);
            } else {
                $this->collectTotals();
            }

            throw new \Exception($cartProducts);
        } else {
            DB::transaction(function () use ($cartProducts, $data) {
                $parentCartItem = null;

                foreach ($cartProducts as $cartProduct) {
                    $cartItem = $this->getItemByProduct($cartProduct, $data);

                    if (isset($cartProduct['parent_id'])) {
                        $cartProduct['parent_id'] = $parentCartItem->id;
                    }

                    if (! $cartItem) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $this->cart->id]));
                    } else {
                        if (
                            isset($cartProduct['parent_id'])
                            && $cartItem->parent_id !== $parentCartItem->id
                        ) {
                            $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                                'cart_id' => $this->cart->id,
                            ]));
                        } else {
                            $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                        }
                    }

                    if (! $parentCartItem) {
                        $parentCartItem = $cartItem;
                    }
                }
            });
        }

        Shipping::removeAllShippingRates();

        $this->collectTotals();

        Event::dispatch('checkout.cart.add.after', $this->cart);

        return $this->cart;
    }

    /**
     * Remove the item from the cart.
     */
    public function removeItem(int $itemId): bool
    {
        if (! $this->cart) {
            return false;
        }

        if (! $this->cart->items->pluck('id')->contains($itemId)) {
            return false;
        }

        Event::dispatch('checkout.cart.delete.before', $itemId);

        Shipping::removeAllShippingRates();

        $result = $this->cartItemRepository->delete($itemId);

        Event::dispatch('checkout.cart.delete.after', $itemId);

        return $result;
    }

    /**
     * Update cart items information.
     */
    public function updateItems(array $data): bool
    {
        if (! $this->cart) {
            throw new \Exception(trans('shop::app.checkout.cart.index.empty-product'));
        }

        foreach ($data['qty'] ?? [] as $itemId => $quantity) {
            $item = $this->cartItemRepository->find($itemId);

            if (! $item) {
                continue;
            }

            if ($item->cart_id !== $this->cart->id) {
                continue;
            }

            if (! $item->product->status) {
                throw new \Exception(trans('shop::app.checkout.cart.inactive'));
            }

            if ($quantity <= 0) {
                $this->removeItem($itemId);

                throw new \Exception(trans('shop::app.checkout.cart.illegal'));
            }

            if (! $item->getTypeInstance()->showQuantityBox()) {
                $quantity = 1;
            }

            $item->quantity = $quantity;

            if (! $this->isItemHaveQuantity($item)) {
                throw new \Exception(trans('shop::app.checkout.cart.inventory-warning'));
            }

            Event::dispatch('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                'quantity' => $quantity,
                'total' => core()->convertPrice($item->base_price * $quantity),
                'total_incl_tax' => core()->convertPrice($item->base_price_incl_tax * $quantity),
                'base_total' => $item->base_price * $quantity,
                'base_total_incl_tax' => $item->base_price_incl_tax * $quantity,
                'total_weight' => $item->weight * $quantity,
                'base_total_weight' => $item->weight * $quantity,
                'additional' => [
                    ...$item->additional,
                    'quantity' => $quantity,
                ],
            ], $itemId);

            Event::dispatch('checkout.cart.update.after', $item);
        }

        Shipping::removeAllShippingRates();

        $this->collectTotals();

        return true;
    }

    /**
     * Get cart item by product.
     */
    public function getItemByProduct(array $data, ?array $parentData = null): ?Contracts\CartItem
    {
        $items = $this->cart->all_items;

        foreach ($items as $item) {
            if ($item->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (
                    ! isset($data['additional']['parent_id'])
                    && ! $item->parent_id
                ) {
                    return $item;
                }

                if ($item->parent?->getTypeInstance()->compareOptions($item->parent->additional, $parentData ?: request()->all())) {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * The saved address a cart address was filled from, when it belongs to this cart's customer.
     */
    public function customerAddressId(array $params): ?int
    {
        return $this->cartAddress->customerAddressId($this->cart, $params);
    }

    /**
     * Update or create billing address.
     */
    public function saveAddresses(array $params): void
    {
        $this->cartAddress->save($this->cart, $params);

        $this->setCustomerPersonnelDetails();

        $this->resetShippingMethod();
    }

    /**
     * Update or create billing address.
     */
    public function updateOrCreateBillingAddress(array $params): CartAddressContract
    {
        return $this->cartAddress->updateOrCreateBilling($this->cart, $params);
    }

    /**
     * Update or create shipping address.
     */
    public function updateOrCreateShippingAddress(array $params): ?CartAddressContract
    {
        return $this->cartAddress->updateOrCreateShipping($this->cart, $params);
    }

    /**
     * Save customer details.
     */
    public function setCustomerPersonnelDetails(): void
    {
        $this->cart->customer_email = $this->cart->customer?->email ?? $this->cart->billing_address->email;
        $this->cart->customer_first_name = $this->cart->customer?->first_name ?? $this->cart->billing_address->first_name;
        $this->cart->customer_last_name = $this->cart->customer?->last_name ?? $this->cart->billing_address->last_name;

        $this->cart->save();
    }

    /**
     * Save shipping method for cart.
     */
    public function saveShippingMethod(string $shippingMethodCode): bool
    {
        if (! $this->cart) {
            return false;
        }

        if (! Shipping::isMethodCodeExists($shippingMethodCode)) {
            return false;
        }

        $this->cart->shipping_method = $shippingMethodCode;
        $this->cart->save();

        return true;
    }

    /**
     * Save shipping method for cart.
     */
    public function resetShippingMethod(): bool
    {
        if (! $this->cart) {
            return false;
        }

        Shipping::removeAllShippingRates();

        $this->cart->shipping_method = null;
        $this->cart->save();

        return true;
    }

    /**
     * Save payment method for cart.
     */
    public function savePaymentMethod(array $params): bool|Contracts\CartPayment
    {
        if (! $this->cart) {
            return false;
        }

        if ($cartPayment = $this->cart->payment) {
            $cartPayment->delete();
        }

        $cartPayment = new CartPayment;

        $cartPayment->method = $params['method'];
        $cartPayment->method_title = core()->getConfigData('sales.payment_methods.'.$params['method'].'.title');
        $cartPayment->cart_id = $this->cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Set coupon code to the cart.
     */
    public function setCouponCode(?string $code): self
    {
        $this->cart->coupon_code = $code;

        $this->cart->save();

        return $this;
    }

    /**
     * Set coupon code to the cart.
     */
    public function removeCouponCode(): self
    {
        return $this->setCouponCode(null);
    }

    /**
     * Move a wishlist item to cart.
     */
    public function moveToCart(WishlistContract $wishlistItem, ?int $quantity = 1): bool
    {
        if (! $wishlistItem->product->getTypeInstance()->canBeMovedFromWishlistToCart($wishlistItem)) {
            return false;
        }

        if (! $wishlistItem->additional) {
            $wishlistItem->additional = ['product_id' => $wishlistItem->product_id];
        }

        $additional = [
            ...$wishlistItem->additional,
            'quantity' => $quantity,
        ];

        $result = $this->addProduct($wishlistItem->product, $additional);

        if ($result) {
            Event::dispatch('customer.wishlist.delete.before', $wishlistItem->id);

            $this->wishlistRepository->delete($wishlistItem->id);

            Event::dispatch('customer.wishlist.delete.after', $wishlistItem->id);

            return true;
        }

        return false;
    }

    /**
     * Move to wishlist items.
     */
    public function moveToWishlist(int $itemId, int $quantity = 1): bool
    {
        $cartItem = $this->cart->items()->find($itemId);

        if (! $cartItem) {
            return false;
        }

        $wishlistItems = $this->wishlistRepository->findWhere([
            'customer_id' => $this->cart->customer_id,
            'product_id' => $cartItem->product_id,
        ]);

        $existingWishlistItem = null;

        foreach ($wishlistItems as $wishlistItem) {
            $options = is_array($wishlistItem->additional) ? $wishlistItem->additional : [];

            if (! $options) {
                $options = ['product_id' => $wishlistItem->product_id];
            }

            if ($cartItem->getTypeInstance()->compareOptions($cartItem->additional, $options)) {
                $existingWishlistItem = $wishlistItem;

                break;
            }
        }

        $event = $existingWishlistItem
            ? 'customer.wishlist.update'
            : 'customer.wishlist.create';

        Event::dispatch($event.'.before', $cartItem->product_id);

        $emptiesCart = ! $this->cart->items->count();

        $wishlistItem = $this->moveItemToWishlist($cartItem, $existingWishlistItem, $quantity, $emptiesCart);

        Event::dispatch($event.'.after', $wishlistItem);

        $this->refreshCart();

        if (! $emptiesCart) {
            $this->collectTotals();
        }

        return true;
    }

    /**
     * Checks if cart has any error.
     */
    public function hasError(): bool
    {
        return ! empty($this->getErrors());
    }

    /**
     * Get Cart Errors.
     */
    public function getErrors()
    {
        if (! $this->cart) {
            return [
                'error_code' => 'CART_NOT_FOUND',
                'message' => trans('shop::app.checkout.cart.index.empty-product'),
            ];
        }

        if (! $this->isItemsHaveSufficientQuantity()) {
            return [
                'error_code' => 'INSUFFICIENT_QUANTITY',
                'message' => trans('shop::app.checkout.cart.inventory-warning'),
            ];
        }

        if (! $this->haveMinimumOrderAmount()) {
            $minimumOrderDescription = core()->getConfigData('sales.order_settings.minimum_order.description');

            return [
                'error_code' => 'MINIMUM_ORDER_AMOUNT',
                'message' => $minimumOrderDescription ?: trans('shop::app.checkout.cart.minimum-order-message'),
                'amount' => core()->formatPrice((int) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: $this->getOrderAmount()),
            ];
        }

        return [];
    }

    /**
     * Check minimum Order Amount of cart.
     */
    public function getOrderAmount(): int
    {
        $minimumOrderAmount = $this->cart->sub_total;

        if (core()->getConfigData('sales.order_settings.minimum_order.include_tax_to_amount')) {
            $minimumOrderAmount += $this->cart->tax_total;
        }

        if (core()->getConfigData('sales.order_settings.minimum_order.include_discount_amount')) {
            $minimumOrderAmount -= $this->cart->tax_total;

            if ($this->cart->discount_amount) {
                $minimumOrderAmount -= $this->cart->discount_amount;
            }
        }

        return $minimumOrderAmount;
    }

    /**
     * Check minimum order.
     */
    public function haveMinimumOrderAmount(): bool
    {
        if (! core()->getConfigData('sales.order_settings.minimum_order.enable')) {
            return true;
        }

        return $this->getOrderAmount() >= ((int) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0);
    }

    /**
     * Checks if all cart items have sufficient quantity.
     */
    public function isItemsHaveSufficientQuantity(): bool
    {
        if (! $this->cart) {
            return false;
        }

        foreach ($this->cart->items as $item) {
            if (! $this->isItemHaveQuantity($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     */
    public function isItemHaveQuantity(Contracts\CartItem $item): bool
    {
        return $item->getTypeInstance()->isItemHaveQuantity($item);
    }

    /**
     * Updates cart totals.
     */
    public function collectTotals(): self
    {
        if (! $this->validateItems()) {
            $this->refreshCart();
        }

        if (! $this->cart) {
            return $this;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $this->cart);

        $this->calculateItemsTax();

        $this->calculateShippingTax();

        $this->refreshCart();

        $this->resetTotals();

        $this->sumItemTotals();

        $this->applySelectedShippingRate();

        $this->roundTotals();

        $this->cart->cart_currency_code = core()->getCurrentCurrencyCode();

        $this->cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $this->cart);

        return $this;
    }

    /**
     * To validate if the product information is changed by admin and the items have been added to the cart before it.
     */
    public function validateItems(): bool
    {
        if (! $this->cart) {
            return false;
        }

        if (! $this->cart->items->count()) {
            $this->removeCart($this->cart);

            return false;
        }

        $isInvalid = false;

        foreach ($this->cart->items as $key => $item) {
            $validationResult = $item->getTypeInstance()->validateCartItem($item);

            if ($validationResult->isItemInactive()) {
                $this->removeItem($item->id);

                $isInvalid = true;

                session()->flash('info', trans('shop::app.checkout.cart.inactive'));
            } else {
                if (Tax::isInclusiveTaxProductPrices()) {
                    $itemBasePrice = $item->base_price_incl_tax;
                } else {
                    $itemBasePrice = $item->base_price;
                }

                $basePrice = ! is_null($item->custom_price) ? $item->custom_price : $itemBasePrice;

                $price = core()->convertPrice($basePrice);

                if ($price != $item->price) {
                    $this->cart->items->put($key, $this->refreshItemPrice($item, $basePrice, $price));
                }
            }

            $isInvalid |= $validationResult->isCartInvalid();
        }

        return ! $isInvalid;
    }

    /**
     * Calculates cart items tax.
     */
    public function calculateItemsTax(): void
    {
        if (! $this->cart) {
            return;
        }

        $this->cartTax->calculateItemsTax($this->cart);
    }

    /**
     * Calculates cart shipping tax.
     */
    public function calculateShippingTax(): void
    {
        if (! $this->cart) {
            return;
        }

        $this->cartTax->calculateShippingTax($this->cart);
    }

    /**
     * Write the wishlist item and drop what it came from in one statement, so a failure on either
     * side cannot leave the product sitting in both the wishlist and the cart.
     */
    protected function moveItemToWishlist(
        Contracts\CartItem $cartItem,
        ?WishlistContract $existingWishlistItem,
        int $quantity,
        bool $emptiesCart
    ): WishlistContract {
        return DB::transaction(function () use ($cartItem, $existingWishlistItem, $quantity, $emptiesCart) {
            if ($existingWishlistItem) {
                $existingAdditional = is_array($existingWishlistItem->additional) ? $existingWishlistItem->additional : [];

                $this->wishlistRepository->update([
                    'additional' => [
                        ...$existingAdditional,
                        'quantity' => ($existingAdditional['quantity'] ?? 1) + $quantity,
                    ],
                ], $existingWishlistItem->id);

                $wishlistItem = $existingWishlistItem;
            } else {
                $wishlistItem = $this->wishlistRepository->create([
                    'channel_id' => $this->cart->channel_id,
                    'customer_id' => $this->cart->customer_id,
                    'product_id' => $cartItem->product_id,
                    'additional' => [
                        ...$cartItem->additional,
                        'quantity' => $quantity,
                    ],
                ]);
            }

            if ($emptiesCart) {
                $this->cartRepository->delete($this->cart->id);
            } else {
                $this->cartItemRepository->delete($cartItem->id);
            }

            return $wishlistItem;
        });
    }

    /**
     * Zero every total before they are summed again from the cart's items.
     */
    protected function resetTotals(): void
    {
        $this->cart->sub_total = $this->cart->base_sub_total = 0;
        $this->cart->sub_total_incl_tax = $this->cart->base_sub_total_incl_tax = 0;

        $this->cart->grand_total = $this->cart->base_grand_total = 0;
        $this->cart->tax_total = $this->cart->base_tax_total = 0;

        $this->cart->discount_amount = $this->cart->base_discount_amount = 0;

        $this->cart->shipping_amount = $this->cart->base_shipping_amount = 0;
        $this->cart->shipping_amount_incl_tax = $this->cart->base_shipping_amount_incl_tax = 0;
    }

    /**
     * Sum the items into the cart's totals, and count what it holds.
     */
    protected function sumItemTotals(): void
    {
        $quantities = 0;

        foreach ($this->cart->items as $item) {
            $this->cart->discount_amount += $item->discount_amount;
            $this->cart->base_discount_amount += $item->base_discount_amount;

            $this->cart->tax_total += $item->tax_amount;
            $this->cart->base_tax_total += $item->base_tax_amount;

            $this->cart->sub_total = (float) $this->cart->sub_total + $item->total;
            $this->cart->base_sub_total = (float) $this->cart->base_sub_total + $item->base_total;

            $this->cart->sub_total_incl_tax = (float) $this->cart->sub_total_incl_tax + $item->total_incl_tax;
            $this->cart->base_sub_total_incl_tax = (float) $this->cart->base_sub_total_incl_tax + $item->base_total_incl_tax;

            $quantities += $item->quantity;
        }

        $this->cart->items_qty = $quantities;

        $this->cart->items_count = $this->cart->items->count();

        $this->cart->grand_total = $this->cart->sub_total + $this->cart->tax_total - $this->cart->discount_amount;
        $this->cart->base_grand_total = $this->cart->base_sub_total + $this->cart->base_tax_total - $this->cart->base_discount_amount;
    }

    /**
     * Add the chosen shipping rate's price, tax and discount on top of the item totals.
     */
    protected function applySelectedShippingRate(): void
    {
        if (! $shipping = $this->cart->selected_shipping_rate) {
            return;
        }

        $this->cart->tax_total += $shipping->tax_amount;
        $this->cart->base_tax_total += $shipping->base_tax_amount;

        $this->cart->shipping_amount = $shipping->price;
        $this->cart->base_shipping_amount = $shipping->base_price;

        $this->cart->shipping_amount_incl_tax = $shipping->price_incl_tax;
        $this->cart->base_shipping_amount_incl_tax = $shipping->base_price_incl_tax;

        $this->cart->grand_total = (float) $this->cart->grand_total + $shipping->tax_amount + $shipping->price - $shipping->discount_amount;
        $this->cart->base_grand_total = (float) $this->cart->base_grand_total + $shipping->base_tax_amount + $shipping->base_price - $shipping->base_discount_amount;

        $this->cart->discount_amount += $shipping->discount_amount;
        $this->cart->base_discount_amount += $shipping->base_discount_amount;
    }

    /**
     * Round every total the storefront displays to two places.
     */
    protected function roundTotals(): void
    {
        $this->cart->discount_amount = round($this->cart->discount_amount, 2);
        $this->cart->base_discount_amount = round($this->cart->base_discount_amount, 2);

        $this->cart->sub_total = round($this->cart->sub_total, 2);
        $this->cart->base_sub_total = round($this->cart->base_sub_total, 2);

        $this->cart->sub_total_incl_tax = round($this->cart->sub_total_incl_tax, 2);
        $this->cart->base_sub_total_incl_tax = round($this->cart->base_sub_total_incl_tax, 2);

        $this->cart->grand_total = round($this->cart->grand_total, 2);

        $this->cart->base_grand_total = round($this->cart->base_grand_total, 2);
    }

    /**
     * Write an item's converted price back, which an inclusive price needs on every pass and an
     * exclusive one only once the exchange rate has moved.
     */
    protected function refreshItemPrice(Contracts\CartItem $item, $basePrice, $price)
    {
        return $this->cartItemRepository->update([
            'price' => $price,
            'price_incl_tax' => $price,
            'base_price' => $basePrice,
            'base_price_incl_tax' => $basePrice,
            'total' => $total = core()->convertPrice($basePrice * $item->quantity),
            'total_incl_tax' => $total,
            'base_total' => ($baseTotal = $basePrice * $item->quantity),
            'base_total_incl_tax' => $baseTotal,
        ], $item->id);
    }
}
