<?php

namespace Webkul\Checkout;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;
use Webkul\Checkout\Helpers\CartAddress;
use Webkul\Checkout\Helpers\CartTax;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartPaymentRepository;
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
     * The session key holding the ids of the isolated buy now carts this visitor owns.
     */
    const BUY_NOW_CARTS_KEY = 'buy_now_cart_ids';

    /**
     * The cart instance, null until one exists.
     */
    protected ?Contracts\Cart $cart = null;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected CartPaymentRepository $cartPaymentRepository,
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

            return;
        }

        $this->cart = $this->sessionCart();
    }

    /**
     * Reload the cart from the database, forgetting it entirely where the row has gone.
     */
    public function refreshCart(): void
    {
        if (! $this->cart) {
            return;
        }

        if (! $cart = $this->cartRepository->find($this->cart->id)) {
            $this->forgetCart();

            return;
        }

        $this->cart = $cart;
    }

    /**
     * Set the cart, pointing the guest session key at it unless it belongs to a customer or is an
     * isolated buy now cart, which must never become the session's basket.
     */
    public function setCart(Contracts\Cart $cart): void
    {
        $this->cart = $cart;

        if (
            $this->cart->customer
            || $this->cart->is_buy_now
        ) {
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
        $cart = $this->cartRepository->create($this->prepareCartData($data));

        $this->setCart($cart);

        return $cart;
    }

    /**
     * Remove the given cart, letting go of it and of the session key only where they named it.
     */
    public function removeCart(Contracts\Cart $cart): void
    {
        Event::dispatch('checkout.cart.destroy.before', $cart->id);

        $this->cartRepository->delete($cart->id);

        if ($this->cart?->id === $cart->id) {
            $this->resetCart();
        }

        if ($this->sessionCartId() === $cart->id) {
            session()->forget('cart');
        }

        Event::dispatch('checkout.cart.destroy.after', $cart->id);
    }

    /**
     * Reset cart.
     */
    public function resetCart(): void
    {
        $this->cart = null;
    }

    /**
     * Activate the cart by id, doing nothing where the row has gone.
     */
    public function activateCart(int $cartId): void
    {
        if (! $this->cartRepository->find($cartId)) {
            return;
        }

        $this->setCart($this->cartRepository->update([
            'is_active' => true,
        ], $cartId));
    }

    /**
     * Deactivates the current cart, letting go of the session key only where it names that cart,
     * so deactivating an isolated buy now cart never drops the shopper's basket pointer.
     */
    public function deActivateCart(): void
    {
        if (! $this->cart) {
            return;
        }

        $cartId = $this->cart->id;

        $this->cartRepository->update(['is_active' => false], $cartId);

        $this->resetCart();

        if ($this->sessionCartId() === $cartId) {
            session()->forget('cart');
        }
    }

    /**
     * Create an isolated cart holding only the bought product, and mirror that product into the
     * shopper's basket so an unfinished buy now stays in it and a completed one is taken back out.
     */
    public function createBuyNowCart(ProductContract $product, array $data): Contracts\Cart
    {
        $basket = $this->cart;

        $buyNowCart = $this->cartRepository->create($this->prepareCartData([
            'is_active' => 0,
            'is_buy_now' => 1,
        ]));

        $this->cart = $buyNowCart;

        try {
            $this->addProduct($product, $data);
        } catch (\Throwable $e) {
            if ($this->cart) {
                $this->removeCart($this->cart);
            }

            $this->cart = $basket;

            throw $e;
        }

        $this->cart = $basket;

        try {
            $this->addProduct($product, $data);
        } catch (\Throwable $e) {
            $this->removeCart($buyNowCart);

            $this->cart = $basket;

            throw $e;
        }

        $this->rememberBuyNowCart($buyNowCart->id);

        return $this->cartRepository->find($buyNowCart->id);
    }

    /**
     * Resolve the request onto a buy now cart named by its id, where this visitor owns it, so the
     * checkout and the gateway act on that cart alone without it becoming the session's basket.
     */
    public function useBuyNowCart(int $cartId): bool
    {
        $cart = $this->cartRepository->find($cartId);

        if (! $this->ownsBuyNowCart($cart)) {
            return false;
        }

        $this->cart = $cart;

        return true;
    }

    /**
     * Take the products a completed buy now cart holds back out of the shopper's basket, undoing the
     * mirror the buy now made, so a finished buy now leaves the basket as it was before it began.
     */
    public function removeBuyNowItemsFromBasket(int $cartId): void
    {
        $buyNowCart = $this->cartRepository->find($cartId);

        if (
            ! $buyNowCart
            || ! $buyNowCart->is_buy_now
        ) {
            return;
        }

        if (! $basket = $this->buyNowBasket($buyNowCart)) {
            return;
        }

        $current = $this->cart;

        $this->setCart($basket);

        foreach ($buyNowCart->items as $buyNowItem) {
            if (! $basketItem = $this->cart->items->firstWhere('product_id', $buyNowItem->product_id)) {
                continue;
            }

            $remaining = $basketItem->quantity - $buyNowItem->quantity;

            if ($remaining > 0) {
                $this->updateItems(['qty' => [$basketItem->id => $remaining]]);
            } else {
                $this->removeItem($basketItem->id);
            }
        }

        $this->collectTotals();

        $this->cart = $current;
    }

    /**
     * This method handles when guest has some of cart products and then logs in.
     */
    public function mergeCart(CustomerContract $customer): void
    {
        $this->adoptBuyNowCarts($customer);

        if (! $guestCart = $this->sessionCart()) {
            return;
        }

        $cart = $this->cartRepository->findOneWhere([
            'customer_id' => $customer->id,
            'is_active' => 1,
        ]);

        if (! $cart) {
            $this->adoptGuestCart($customer, $guestCart);

            return;
        }

        $this->setCart($cart);

        $this->mergeGuestCartItems($guestCart);
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
        }

        $this->persistCartProducts($cartProducts, $data);

        $this->cart->unsetRelation('items');

        $this->cart->unsetRelation('all_items');

        Shipping::removeAllShippingRates();

        $this->collectTotals();

        if (! $this->cart) {
            throw new \Exception(trans('shop::app.checkout.cart.index.empty-product'));
        }

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

        if (! $this->cart->items()->find($itemId)) {
            return false;
        }

        Event::dispatch('checkout.cart.delete.before', $itemId);

        Shipping::removeAllShippingRates();

        $result = $this->cartItemRepository->delete($itemId);

        if (! $result) {
            return $result;
        }

        $this->cart->unsetRelation('items');

        $this->cart->unsetRelation('all_items');

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
            $item = $this->updatableCartItem($itemId);

            if (! $item) {
                continue;
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

            $item = $this->cartItemRepository->update($this->cartItemQuantityPayload($item, $quantity), $itemId);

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
        if (! $this->cart) {
            return null;
        }

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
        if (! $this->cart) {
            return null;
        }

        return $this->cartAddress->customerAddressId($this->cart, $params);
    }

    /**
     * Persist the cart's addresses, sync the customer details and clear the shipping method.
     */
    public function saveAddresses(array $params): void
    {
        if (! $this->cart) {
            return;
        }

        $this->cartAddress->save($this->cart, $params);

        $this->setCustomerPersonnelDetails();

        $this->resetShippingMethod();
    }

    /**
     * Update or create billing address.
     */
    public function updateOrCreateBillingAddress(array $params): CartAddressContract
    {
        if (! $this->cart) {
            throw new \Exception(trans('shop::app.checkout.cart.index.empty-product'));
        }

        return $this->cartAddress->updateOrCreateBilling($this->cart, $params);
    }

    /**
     * Update or create shipping address.
     */
    public function updateOrCreateShippingAddress(array $params): ?CartAddressContract
    {
        if (! $this->cart) {
            throw new \Exception(trans('shop::app.checkout.cart.index.empty-product'));
        }

        return $this->cartAddress->updateOrCreateShipping($this->cart, $params);
    }

    /**
     * Save customer details.
     */
    public function setCustomerPersonnelDetails(): void
    {
        if (! $this->cart) {
            return;
        }

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
     * Clear the chosen shipping method and drop the rates collected for it.
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

        $method = $params['method'] ?? null;

        if (! $method) {
            return false;
        }

        $methodTitle = core()->getConfigData('sales.payment_methods.'.$method.'.title');

        return DB::transaction(function () use ($method, $methodTitle) {
            if ($cartPayment = $this->cart->payment) {
                $cartPayment->delete();
            }

            $cartPayment = $this->cartPaymentRepository->create([
                'method' => $method,
                'method_title' => $methodTitle,
                'cart_id' => $this->cart->id,
            ]);

            $this->cart->setRelation('payment', $cartPayment);

            return $cartPayment;
        });
    }

    /**
     * Set coupon code to the cart.
     */
    public function setCouponCode(?string $code): self
    {
        if (! $this->cart) {
            return $this;
        }

        $this->cart->coupon_code = $code;

        $this->cart->save();

        return $this;
    }

    /**
     * Remove the coupon code from the cart.
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
     * Move a cart item to the customer's wishlist.
     */
    public function moveToWishlist(int $itemId, int $quantity = 1): bool
    {
        if (! $this->cart) {
            return false;
        }

        $cartItem = $this->cart->items()->find($itemId);

        if (! $cartItem) {
            return false;
        }

        $existingWishlistItem = $this->matchingWishlistItem($cartItem);

        $event = $existingWishlistItem
            ? 'customer.wishlist.update'
            : 'customer.wishlist.create';

        Event::dispatch($event.'.before', $cartItem->product_id);

        Shipping::removeAllShippingRates();

        Event::dispatch('checkout.cart.delete.before', $cartItem->id);

        $movedWishlistItem = $this->moveItemToWishlist($cartItem, $existingWishlistItem, $quantity);

        Event::dispatch('checkout.cart.delete.after', $cartItem->id);

        Event::dispatch($event.'.after', $movedWishlistItem);

        $this->refreshCart();

        $this->collectTotals();

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
     * Get the cart errors, as a descriptor keyed error_code and message, plus amount on the
     * minimum-order error, or an empty array when the cart is valid.
     */
    public function getErrors(): array
    {
        if (! $this->cart) {
            return $this->cartNotFoundError();
        }

        if (! $this->isItemsHaveSufficientQuantity()) {
            return $this->insufficientQuantityError();
        }

        if (! $this->haveMinimumOrderAmount()) {
            return $this->minimumOrderAmountError();
        }

        return [];
    }

    /**
     * The cart amount the minimum-order rule is measured against.
     */
    public function getOrderAmount(): float
    {
        if (! $this->cart) {
            return 0.0;
        }

        $minimumOrderAmount = $this->cart->sub_total;

        if (core()->getConfigData('sales.order_settings.minimum_order.include_discount_amount')) {
            return $minimumOrderAmount - $this->cart->discount_amount;
        }

        if (core()->getConfigData('sales.order_settings.minimum_order.include_tax_to_amount')) {
            $minimumOrderAmount += $this->cart->tax_total;
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
     * Checks whether one cart item has sufficient quantity.
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

        if (! $this->cart) {
            return $this;
        }

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

        Event::dispatch('checkout.cart.validate.items.before', $this->cart);

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

            $isInvalid = $isInvalid || $validationResult->isCartInvalid();
        }

        Event::dispatch('checkout.cart.validate.items.after', $this->cart);

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

        Event::dispatch('checkout.cart.calculate.items.tax.before', $this->cart);

        $this->cartTax->calculateItemsTax($this->cart);

        Event::dispatch('checkout.cart.calculate.items.tax.after', $this->cart);
    }

    /**
     * Calculates cart shipping tax.
     */
    public function calculateShippingTax(): void
    {
        if (! $this->cart) {
            return;
        }

        Event::dispatch('checkout.cart.calculate.shipping.tax.before', $this->cart);

        $this->cartTax->calculateShippingTax($this->cart);

        Event::dispatch('checkout.cart.calculate.shipping.tax.after', $this->cart);
    }

    /**
     * The wishlist row this cart item would merge into, matched on the options it carries.
     */
    protected function matchingWishlistItem(Contracts\CartItem $cartItem): ?WishlistContract
    {
        $wishlistItems = $this->wishlistRepository->findWhere([
            'customer_id' => $this->cart->customer_id,
            'product_id' => $cartItem->product_id,
        ]);

        foreach ($wishlistItems as $candidate) {
            $options = is_array($candidate->additional) ? $candidate->additional : [];

            if (! $options) {
                $options = ['product_id' => $candidate->product_id];
            }

            if ($cartItem->getTypeInstance()->compareOptions($cartItem->additional, $options)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * The cart id the session key names, or null where the key is absent or not the shape we wrote.
     */
    protected function sessionCartId(): ?int
    {
        $cart = session()->get('cart');

        if (
            ! is_object($cart)
            || ! isset($cart->id)
        ) {
            return null;
        }

        return (int) $cart->id;
    }

    /**
     * The guest cart the session key names, forgetting a key that no longer names a live one of
     * their own so a dead key heals on the request that meets it.
     */
    protected function sessionCart(): ?Contracts\Cart
    {
        if ($cartId = $this->sessionCartId()) {
            $cart = $this->cartRepository->find($cartId);

            if (
                $cart
                && $cart->is_active
                && ! $cart->customer_id
            ) {
                return $cart;
            }
        }

        session()->forget('cart');

        return null;
    }

    /**
     * Drop the cart and the session key that points at it, so neither outlives the other.
     */
    protected function forgetCart(): void
    {
        $this->resetCart();

        if (session()->has('cart')) {
            session()->forget('cart');
        }
    }

    /**
     * The attributes a new cart is created with — channel, currency and, for a signed-in shopper,
     * their details — so the basket and a buy now cart are both built from one place.
     */
    protected function prepareCartData(array $data): array
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

        return $data;
    }

    /**
     * The shopper's active basket a buy now cart was mirrored into — the signed-in customer's own, or
     * the guest session's — so a completed buy now can be taken back out of it.
     */
    protected function buyNowBasket(Contracts\Cart $buyNowCart): ?Contracts\Cart
    {
        if ($buyNowCart->customer_id) {
            return $this->cartRepository->findOneWhere([
                'customer_id' => $buyNowCart->customer_id,
                'is_active' => 1,
            ]);
        }

        return $this->sessionCart();
    }

    /**
     * Whether a buy now cart named by its id belongs to this visitor — the signed-in customer who
     * owns it, or a guest whose session created it — so one visitor cannot drive another's cart.
     */
    protected function ownsBuyNowCart(?Contracts\Cart $cart): bool
    {
        if (
            ! $cart
            || ! $cart->is_buy_now
        ) {
            return false;
        }

        if (in_array((int) $cart->id, $this->rememberedBuyNowCartIds(), true)) {
            return true;
        }

        $customer = auth()->guard()->user();

        return $customer
            && (int) $cart->customer_id === (int) $customer->id;
    }

    /**
     * Remember a buy now cart's id against the guest session that created it, so the ownership
     * check can recognise it later without a customer to key on.
     */
    protected function rememberBuyNowCart(int $cartId): void
    {
        $ids = $this->rememberedBuyNowCartIds();

        $ids[] = $cartId;

        session()->put(self::BUY_NOW_CARTS_KEY, array_values(array_unique($ids)));
    }

    /**
     * The buy now cart ids remembered against this session, each cast to an integer.
     */
    protected function rememberedBuyNowCartIds(): array
    {
        return array_map('intval', session()->get(self::BUY_NOW_CARTS_KEY, []));
    }

    /**
     * Hand the buy now carts a guest started over to the customer who signs in, so a buy now begun
     * before signing in can still be checked out afterwards.
     */
    protected function adoptBuyNowCarts(CustomerContract $customer): void
    {
        foreach ($this->rememberedBuyNowCartIds() as $cartId) {
            $cart = $this->cartRepository->find($cartId);

            if (
                $cart
                && $cart->is_buy_now
                && ! $cart->customer_id
            ) {
                $this->cartRepository->update([
                    'customer_id' => $customer->id,
                    'is_guest' => 0,
                ], $cartId);
            }
        }
    }

    /**
     * Hand the guest's own cart to the customer who just logged in, there being nothing to merge
     * it into.
     */
    protected function adoptGuestCart(CustomerContract $customer, Contracts\Cart $guestCart): void
    {
        $cart = $this->cartRepository->update([
            'customer_id' => $customer->id,
            'is_guest' => 0,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
            'customer_email' => $customer->email,
        ], $guestCart->id);

        session()->forget('cart');

        $this->setCart($cart);
    }

    /**
     * Add what the guest had to the cart already in play, dropping any line that will no longer
     * go in rather than refusing the login over it.
     */
    protected function mergeGuestCartItems(Contracts\Cart $guestCart): void
    {
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
     * The error that says there is no cart to check out.
     */
    protected function cartNotFoundError(): array
    {
        return [
            'error_code' => 'CART_NOT_FOUND',
            'message' => trans('shop::app.checkout.cart.index.empty-product'),
        ];
    }

    /**
     * The error that says the cart holds more of something than there is to sell.
     */
    protected function insufficientQuantityError(): array
    {
        return [
            'error_code' => 'INSUFFICIENT_QUANTITY',
            'message' => trans('shop::app.checkout.cart.inventory-warning'),
        ];
    }

    /**
     * The error that says the cart is worth less than the store will accept, carrying the amount
     * it has to reach.
     */
    protected function minimumOrderAmountError(): array
    {
        $description = core()->getConfigData('sales.order_settings.minimum_order.description');

        $configuredMinimum = (int) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount');

        return [
            'error_code' => 'MINIMUM_ORDER_AMOUNT',
            'message' => $description ?: trans('shop::app.checkout.cart.minimum-order-message'),
            'amount' => core()->formatPrice($configuredMinimum ?: $this->getOrderAmount()),
        ];
    }

    /**
     * The cart item an update may touch, or nothing where it is not this cart's to update.
     */
    protected function updatableCartItem($itemId): ?Contracts\CartItem
    {
        $item = $this->cartItemRepository->find($itemId);

        if (
            ! $item
            || $item->cart_id !== $this->cart->id
        ) {
            return null;
        }

        if (! $item->product->status) {
            throw new \Exception(trans('shop::app.checkout.cart.inactive'));
        }

        return $item;
    }

    /**
     * What a cart item's row becomes once its quantity changes.
     */
    protected function cartItemQuantityPayload(Contracts\CartItem $item, $quantity): array
    {
        return [
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
        ];
    }

    /**
     * Write every line the product type prepared, in one statement so a composite product cannot
     * be left half in the cart.
     */
    protected function persistCartProducts(array $cartProducts, array $data): void
    {
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

    /**
     * Write the wishlist item and drop what it came from in one statement, so a failure on either
     * side cannot leave the product sitting in both the wishlist and the cart.
     */
    protected function moveItemToWishlist(
        Contracts\CartItem $cartItem,
        ?WishlistContract $existingWishlistItem,
        int $quantity
    ): WishlistContract {
        return DB::transaction(function () use ($cartItem, $existingWishlistItem, $quantity) {
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

            $this->cartItemRepository->delete($cartItem->id);

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
    protected function refreshItemPrice(Contracts\CartItem $item, $basePrice, $price): Contracts\CartItem
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
