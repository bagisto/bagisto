<?php

namespace Webkul\Checkout;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Traits\CartCoupons;
use Webkul\Checkout\Traits\CartTools;
use Webkul\Checkout\Traits\CartValidators;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Tax\Helpers\Tax;
use Webkul\Tax\Repositories\TaxCategoryRepository;

class Cart
{
    use CartCoupons, CartTools, CartValidators;

    /**
     * Cart repository instance.
     *
     * @var \Webkul\Checkout\Repositories\CartRepository
     */
    protected $cartRepository;

    /**
     * Cart item repository instance.
     *
     * @var \Webkul\Checkout\Repositories\CartItemRepository
     */
    protected $cartItemRepository;

    /**
     * Cart address repository instance.
     *
     * @var \Webkul\Checkout\Repositories\CartAddressRepository
     */
    protected $cartAddressRepository;

    /**
     * Product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Tax category repository instance.
     *
     * @var \Webkul\Tax\Repositories\TaxCategoryRepository
     */
    protected $taxCategoryRepository;

    /**
     * Wishlist repository instance.
     *
     * @var \Webkul\Customer\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * Customer address repository instance.
     *
     * @var \Webkul\Customer\Repositories\CustomerAddressRepository
     */
    protected $customerAddressRepository;

    /**
     * Create a new class instance.
     *
     * @param  \Webkul\Checkout\Repositories\CartRepository             $cartRepository
     * @param  \Webkul\Checkout\Repositories\CartItemRepository         $cartItemRepository
     * @param  \Webkul\Checkout\Repositories\CartAddressRepository      $cartAddressRepository
     * @param  \Webkul\Product\Repositories\ProductRepository           $productRepository
     * @param  \Webkul\Tax\Repositories\TaxCategoryRepository           $taxCategoryRepository
     * @param  \Webkul\Customer\Repositories\WishlistRepository         $wishlistRepository
     * @param  \Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     * @return void
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        CartAddressRepository $cartAddressRepository,
        ProductRepository $productRepository,
        TaxCategoryRepository $taxCategoryRepository,
        WishlistRepository $wishlistRepository,
        CustomerAddressRepository $customerAddressRepository
    ) {
        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->cartAddressRepository = $cartAddressRepository;

        $this->productRepository = $productRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->wishlistRepository = $wishlistRepository;

        $this->customerAddressRepository = $customerAddressRepository;
    }

    /**
     * Returns cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart|null
     */
    public function getCart(): ?\Webkul\Checkout\Contracts\Cart
    {
        $cart = null;

        if (auth()->guard()->check()) {
            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => auth()->guard()->user()->id,
                'is_active'   => 1,
            ]);
        } else if (session()->has('cart')) {
            $cart = $this->cartRepository->find(session()->get('cart')->id);
        }

        $this->removeInactiveItems($cart);

        return $cart;
    }

    /**
     * Get cart item by product.
     *
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\CartItem|void
     */
    public function getItemByProduct($data)
    {
        $items = $this->getCart()->all_items;

        foreach ($items as $item) {
            if ($item->product->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (isset($data['additional']['parent_id'])) {
                    if ($item->parent->product->getTypeInstance()->compareOptions($item->parent->additional, $data['additional'])) {
                        return $item;
                    }
                } else {
                    return $item;
                }
            }
        }
    }

    /**
     * Add items in a cart with some cart and item details.
     *
     * @param  int  $productId
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\Cart|string|array
     * @throws Exception
     */
    public function addProduct($productId, $data)
    {
        Event::dispatch('checkout.cart.add.before', $productId);

        $cart = $this->getCart();

        if (! $cart && ! $cart = $this->create($data)) {
            return ['warning' => __('shop::app.checkout.cart.item.error-add')];
        }

        $product = $this->productRepository->findOneByField('id', $productId);

        if ($product->status === 0) {
            return ['info' => __('shop::app.checkout.cart.item.inactive-add')];
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            $this->collectTotals();

            if (count($cart->all_items) <= 0) {
                session()->forget('cart');
            }

            throw new Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (isset($cartProduct['parent_id']) && $cartItem->parent_id !== $parentCartItem->id) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }
        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->getCart();
    }

    /**
     * Create new cart instance.
     *
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\Cart|null
     */
    public function create($data)
    {
        $cartData = [
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => core()->getBaseCurrencyCode(),
            'base_currency_code'    => core()->getBaseCurrencyCode(),
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
        ];

        /**
         * Fill in the customer data, as far as possible.
         */
        if (auth()->guard()->check()) {
            $cartData['customer_id'] = auth()->guard()->user()->id;
            $cartData['is_guest'] = 0;
            $cartData['customer_first_name'] = auth()->guard()->user()->first_name;
            $cartData['customer_last_name'] = auth()->guard()->user()->last_name;
            $cartData['customer_email'] = auth()->guard()->user()->email;
        } else {
            $cartData['is_guest'] = 1;
        }

        $cart = $this->cartRepository->create($cartData);

        if (! $cart) {
            session()->flash('error', __('shop::app.checkout.cart.create-error'));

            return;
        }

        $this->putCart($cart);

        return $cart;
    }

    /**
     * Update cart items information.
     *
     * @param  array  $data
     * @return bool|void|Exception
     */
    public function updateItems($data)
    {
        foreach ($data['qty'] as $itemId => $quantity) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            if (! $item) {
                continue;
            }

            if ($item->product && $item->product->status === 0) {
                throw new Exception(__('shop::app.checkout.cart.item.inactive'));
            }

            if ($quantity <= 0) {
                $this->removeItem($itemId);

                throw new Exception(__('shop::app.checkout.cart.quantity.illegal'));
            }

            $item->quantity = $quantity;

            if (! $this->isItemHaveQuantity($item)) {
                throw new Exception(__('shop::app.checkout.cart.quantity.inventory_warning'));
            }

            Event::dispatch('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                'quantity'          => $quantity,
                'total'             => core()->convertPrice($item->price * $quantity),
                'base_total'        => $item->price * $quantity,
                'total_weight'      => $item->weight * $quantity,
                'base_total_weight' => $item->weight * $quantity,
            ], $itemId);

            Event::dispatch('checkout.cart.update.after', $item);
        }

        $this->collectTotals();

        return true;
    }

    /**
     * Remove the item from the cart.
     *
     * @param  int  $itemId
     * @return boolean
     */
    public function removeItem($itemId)
    {
        Event::dispatch('checkout.cart.delete.before', $itemId);

        if (! $cart = $this->getCart()) {
            return false;
        }

        if ($cartItem = $cart->items()->find($itemId)) {
            $cartItem->delete();

            if ($cart->items->count() == 0) {
                $this->cartRepository->delete($cart->id);

                if (session()->has('cart')) {
                    session()->forget('cart');
                }
            }

            Shipping::collectRates();

            Event::dispatch('checkout.cart.delete.after', $itemId);

            $this->collectTotals();

            return true;
        }

        return false;
    }

    /**
     * Remove cart items, whose product is inactive.
     *
     * @param \Webkul\Checkout\Models\Cart|null $cart
     * @return \Webkul\Checkout\Models\Cart|null
     */
    public function removeInactiveItems(CartModel $cart = null): ?CartModel
    {
        if (! $cart) {
            return $cart;
        }

        foreach ($cart->items as $item) {
            if ($this->isCartItemInactive($item)) {

                $this->cartItemRepository->delete($item->id);

                if ($cart->items->count() == 0) {
                    $this->cartRepository->delete($cart->id);

                    if (session()->has('cart')) {
                        session()->forget('cart');
                    }
                }

                session()->flash('info', __('shop::app.checkout.cart.item.inactive'));
            }
        }

        $cart->save();

        return $cart;
    }

    /**
     * Save customer address.
     *
     * @param  array  $data
     * @return bool
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function saveCustomerAddress($data): bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        $billingAddressData = $this->gatherBillingAddress($data, $cart);

        $shippingAddressData = $this->gatherShippingAddress($data, $cart);

        $this->saveAddressesWhenRequested($data, $billingAddressData, $shippingAddressData);

        $this->linkAddresses($cart, $billingAddressData, $shippingAddressData);

        $this->assignCustomerFields($cart);

        $cart->save();

        $this->collectTotals();

        return true;
    }

    /**
     * Save shipping method for cart.
     *
     * @param  string  $shippingMethodCode
     * @return bool
     */
    public function saveShippingMethod($shippingMethodCode): bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        if (! Shipping::isMethodCodeExists($shippingMethodCode)) {
            return false;
        }

        $cart->shipping_method = $shippingMethodCode;
        $cart->save();

        return true;
    }

    /**
     * Save payment method for cart.
     *
     * @param  string  $payment
     * @return \Webkul\Checkout\Contracts\CartPayment
     */
    public function savePaymentMethod($payment)
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        if ($cartPayment = $cart->payment) {
            $cartPayment->delete();
        }

        $cartPayment = new CartPayment;

        $cartPayment->method = $payment['method'];
        $cartPayment->cart_id = $cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Updates cart totals.
     *
     * @return void
     */
    public function collectTotals(): void
    {
        if (! $this->validateItems()) {
            return;
        }

        if (! $cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $cart);

        $this->calculateItemsTax();
        $cart->refresh();

        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->tax_total = $cart->base_tax_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;

        foreach ($cart->items as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;

            $cart->sub_total = (float) $cart->sub_total + $item->total;
            $cart->base_sub_total = (float) $cart->base_sub_total + $item->base_total;
        }

        $cart->tax_total = Tax::getTaxTotal($cart, false);
        $cart->base_tax_total = Tax::getTaxTotal($cart, true);

        $cart->grand_total = $cart->sub_total + $cart->tax_total - $cart->discount_amount;
        $cart->base_grand_total = $cart->base_sub_total + $cart->base_tax_total - $cart->base_discount_amount;

        if ($shipping = $cart->selected_shipping_rate) {
            $cart->grand_total = (float) $cart->grand_total + $shipping->price - $shipping->discount_amount;
            $cart->base_grand_total = (float) $cart->base_grand_total + $shipping->base_price - $shipping->base_discount_amount;

            $cart->discount_amount += $shipping->discount_amount;
            $cart->base_discount_amount += $shipping->base_discount_amount;
        }

        $cart = $this->finalizeCartTotals($cart);

        $quantities = 0;

        foreach ($cart->items as $item) {
            $quantities = $quantities + $item->quantity;
        }

        $cart->items_count = $cart->items->count();

        $cart->items_qty = $quantities;

        $cart->cart_currency_code = core()->getCurrentCurrencyCode();

        $cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $cart);
    }

    /**
     * Calculates cart items tax.
     *
     * @return void
     */
    public function calculateItemsTax(): void
    {
        if (! $cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.calculate.items.tax.before', $cart);

        foreach ($cart->items as $item) {
            $taxCategory = $this->taxCategoryRepository->find($item->product->tax_category_id);

            if (! $taxCategory) {
                continue;
            }

            if ($item->product->getTypeInstance()->isStockable()) {
                $address = $cart->shipping_address;
            } else {
                $address = $cart->billing_address;
            }

            if ($address === null && auth()->guard()->check()) {
                $address = auth()->guard()->user()->addresses()
                    ->where('default_address', 1)->first();
            }

            if ($address === null) {
                $address = Tax::getDefaultAddress();
            }

            $item = $this->setItemTaxToZero($item);

            Tax::isTaxApplicableInCurrentAddress($taxCategory, $address, function ($rate) use ($cart, $item) {
                $item->tax_percent = $rate->tax_rate;

                $item->tax_amount = round(($item->total * $rate->tax_rate) / 100, 4);

                $item->base_tax_amount = round(($item->base_total * $rate->tax_rate) / 100, 4);
            });

            $item->save();
        }

        Event::dispatch('checkout.cart.calculate.items.tax.after', $cart);
    }

    /**
     * To validate if the product information is changed by admin and the items have been added to the cart before it.
     *
     * @return bool
     */
    public function validateItems(): bool
    {
        if (! $cart = $this->getCart()) {
            return false;
        }

        if (count($cart->items) === 0) {
            $this->cartRepository->delete($cart->id);

            return false;
        }

        $isInvalid = false;

        foreach ($cart->items as $item) {
            $validationResult = $item->product->getTypeInstance()->validateCartItem($item);

            if ($validationResult->isItemInactive()) {
                $this->removeItem($item->id);

                $isInvalid = true;

                session()->flash('info', __('shop::app.checkout.cart.item.inactive'));
            }

            $price = ! is_null($item->custom_price) ? $item->custom_price : $item->base_price;

            $this->cartItemRepository->update([
                'price'      => core()->convertPrice($price),
                'base_price' => $price,
                'total'      => core()->convertPrice($price * $item->quantity),
                'base_total' => $price * $item->quantity,
            ], $item->id);

            $isInvalid |= $validationResult->isCartInvalid();
        }

        return ! $isInvalid;
    }

    /**
     * Prepare data for order.
     *
     * @return array
     */
    public function prepareDataForOrder(): array
    {
        $data = $this->toArray();

        $finalData = [
            'cart_id'               => $this->getCart()->id,
            'customer_id'           => $data['customer_id'],
            'is_guest'              => $data['is_guest'],
            'customer_email'        => $data['customer_email'],
            'customer_first_name'   => $data['customer_first_name'],
            'customer_last_name'    => $data['customer_last_name'],
            'customer'              => auth()->guard()->check() ? auth()->guard()->user() : null,
            'total_item_count'      => $data['items_count'],
            'total_qty_ordered'     => $data['items_qty'],
            'base_currency_code'    => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code'   => $data['cart_currency_code'],
            'grand_total'           => $data['grand_total'],
            'base_grand_total'      => $data['base_grand_total'],
            'sub_total'             => $data['sub_total'],
            'base_sub_total'        => $data['base_sub_total'],
            'tax_amount'            => $data['tax_total'],
            'base_tax_amount'       => $data['base_tax_total'],
            'coupon_code'           => $data['coupon_code'],
            'applied_cart_rule_ids' => $data['applied_cart_rule_ids'],
            'discount_amount'       => $data['discount_amount'],
            'base_discount_amount'  => $data['base_discount_amount'],
            'billing_address'       => Arr::except($data['billing_address'], ['id', 'cart_id']),
            'payment'               => Arr::except($data['payment'], ['id', 'cart_id']),
            'channel'               => core()->getCurrentChannel(),
        ];

        if ($this->getCart()->haveStockableItems()) {
            $finalData = array_merge($finalData, [
                'shipping_method'               => $data['selected_shipping_rate']['method'],
                'shipping_title'                => $data['selected_shipping_rate']['carrier_title'] . ' - ' . $data['selected_shipping_rate']['method_title'],
                'shipping_description'          => $data['selected_shipping_rate']['method_description'],
                'shipping_amount'               => $data['selected_shipping_rate']['price'],
                'base_shipping_amount'          => $data['selected_shipping_rate']['base_price'],
                'shipping_address'              => Arr::except($data['shipping_address'], ['id', 'cart_id']),
                'shipping_discount_amount'      => $data['selected_shipping_rate']['discount_amount'],
                'base_shipping_discount_amount' => $data['selected_shipping_rate']['base_discount_amount'],
            ]);
        }

        foreach ($data['items'] as $item) {
            $finalData['items'][] = $this->prepareDataForOrderItem($item);
        }

        if ($finalData['payment']['method'] === 'paypal_smart_button') {
            $finalData['payment']['additional'] = request()->get('orderData');
        }

        return $finalData;
    }

    /**
     * Prepares data for order item.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareDataForOrderItem($data): array
    {
        $locale = ['locale' => core()->getCurrentLocale()->code];

        $finalData = [
            'product'              => $this->productRepository->find($data['product_id']),
            'sku'                  => $data['sku'],
            'type'                 => $data['type'],
            'name'                 => $data['name'],
            'weight'               => $data['weight'],
            'total_weight'         => $data['total_weight'],
            'qty_ordered'          => $data['quantity'],
            'price'                => $data['price'],
            'base_price'           => $data['base_price'],
            'total'                => $data['total'],
            'base_total'           => $data['base_total'],
            'tax_percent'          => $data['tax_percent'],
            'tax_amount'           => $data['tax_amount'],
            'base_tax_amount'      => $data['base_tax_amount'],
            'discount_percent'     => $data['discount_percent'],
            'discount_amount'      => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            'additional'           => is_array($data['additional']) ? array_merge($data['additional'], $locale) : $locale,
        ];

        if (isset($data['children']) && $data['children']) {
            foreach ($data['children'] as $child) {
                /**
                 * - For bundle, child quantity will not be zero.
                 *
                 * - For configurable, parent one will be added as child one is zero.
                 *
                 * - In testing phase.
                 */
                $child['quantity'] = $child['quantity'] ? $child['quantity'] * $data['quantity'] : $data['quantity'];

                $finalData['children'][] = $this->prepareDataForOrderItem($child);
            }
        }

        return $finalData;
    }

    /**
     * Returns cart details in array.
     *
     * @return array
     */
    public function toArray()
    {
        $cart = $this->getCart();

        $data = $cart->toArray();

        $data['billing_address'] = $cart->billing_address->toArray();

        if ($cart->haveStockableItems()) {
            $data['shipping_address'] = $cart->shipping_address->toArray();

            $data['selected_shipping_rate'] = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->toArray() : 0.0;
        }

        $data['payment'] = $cart->payment->toArray();

        $data['items'] = $cart->items->toArray();

        return $data;
    }

    /**
     * Set Item tax to zero.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return \Webkul\Checkout\Contracts\CartItem
     */
    protected function setItemTaxToZero(\Webkul\Checkout\Contracts\CartItem $item): \Webkul\Checkout\Contracts\CartItem
    {
        $item->tax_percent = 0;
        $item->tax_amount = 0;
        $item->base_tax_amount = 0;

        return $item;
    }

    /**
     * Transfer the user profile information into the cart/into the order.
     *
     * When logged in as guest or the customer profile is not complete, we use the
     * billing address to fill the order customer_ data.
     *
     * @param \Webkul\Checkout\Contracts\Cart $cart
     */
    private function assignCustomerFields(\Webkul\Checkout\Contracts\Cart $cart): void
    {
        if (
            auth()->guard()->check()
            && ($user = auth()->guard()->user())
            && $this->profileIsComplete($user)
        ) {
            $cart->customer_email = $user->email;
            $cart->customer_first_name = $user->first_name;
            $cart->customer_last_name = $user->last_name;
        } else {
            $cart->customer_email = $cart->billing_address->email;
            $cart->customer_first_name = $cart->billing_address->first_name;
            $cart->customer_last_name = $cart->billing_address->last_name;
        }
    }

    /**
     * Round cart totals.
     *
     * @param \Webkul\Checkout\Models\Cart $cart
     * @return \Webkul\Checkout\Models\Cart
     */
    private function finalizeCartTotals(CartModel $cart): CartModel
    {
        $cart->discount_amount = round($cart->discount_amount, 2);
        $cart->base_discount_amount = round($cart->base_discount_amount, 2);

        $cart->sub_total = round($cart->sub_total, 2);
        $cart->base_sub_total = round($cart->base_sub_total, 2);

        $cart->grand_total = round($cart->grand_total, 2);
        $cart->base_grand_total = round($cart->base_grand_total, 2);

        return $cart;
    }

    /**
     * Returns true, if cart item is inactive.
     *
     * @param \Webkul\Checkout\Contracts\CartItem $item
     * @return bool
     */
    private function isCartItemInactive(\Webkul\Checkout\Contracts\CartItem $item): bool
    {
        static $loadedCartItem = [];

        if (array_key_exists($item->product_id, $loadedCartItem)) {
            return $loadedCartItem[$item->product_id];
        }

        return $loadedCartItem[$item->product_id] = $item->product->getTypeInstance()->isCartItemInactive($item);
    }

    /**
     * Is profile is complete.
     *
     * @param  $user
     * @return bool
     */
    private function profileIsComplete($user): bool
    {
        return $user->email && $user->first_name && $user->last_name;
    }

    /**
     * Fill customer attributes.
     *
     * @return array
     */
    private function fillCustomerAttributes(): array
    {
        $attributes = [];

        $user = auth()->guard()->user();

        if ($user) {
            $attributes['first_name'] = $user->first_name;
            $attributes['last_name'] = $user->last_name;
            $attributes['email'] = $user->email;
            $attributes['customer_id'] = $user->id;
        }

        return $attributes;
    }

    /**
     * Fill address attributes.
     *
     * @return array
     */
    private function fillAddressAttributes(array $addressAttributes): array
    {
        $attributes = [];

        $cartAddress = new CartAddress();

        foreach ($cartAddress->getFillable() as $attribute) {
            if (isset($addressAttributes[$attribute])) {
                $attributes[$attribute] = $addressAttributes[$attribute];
            }
        }

        return $attributes;
    }

    /**
     * Save addresses when requested.
     *
     * @param  array  $data
     * @param  array  $billingAddress
     * @param  array  $shippingAddress
     * @return void
     */
    private function saveAddressesWhenRequested(
        array $data,
        array $billingAddress,
        array $shippingAddress
    ): void {
        $shippingAddress['cart_id'] = $billingAddress['cart_id'] = null;

        if (isset($data['billing']['save_as_address']) && $data['billing']['save_as_address']) {
            $this->customerAddressRepository->create($billingAddress);
        }

        if (isset($data['shipping']['save_as_address']) && $data['shipping']['save_as_address']) {
            $this->customerAddressRepository->create($shippingAddress);
        }
    }

    /**
     * Gather billing address.
     *
     * @param  $data
     * @param  $cart
     * @return array
     */
    private function gatherBillingAddress($data, \Webkul\Checkout\Models\Cart $cart): array
    {
        $customerAddress = [];

        if (isset($data['billing']['address_id']) && $data['billing']['address_id']) {
            $customerAddress = $this
                ->customerAddressRepository
                ->findOneWhere(['id' => $data['billing']['address_id']])
                ->toArray();
        }

        $billingAddress = array_merge(
            $customerAddress,
            $data['billing'],
            ['cart_id' => $cart->id],
            $this->fillCustomerAttributes(),
            $this->fillAddressAttributes($data['billing'])
        );

        return $billingAddress;
    }

    /**
     * Gather shipping address.
     *
     * @param  array  $data
     * @param  \Webkul\Checkout\Cart|null  $cart
     * @return array
     */
    private function gatherShippingAddress($data, \Webkul\Checkout\Models\Cart $cart): array
    {
        $customerAddress = [];

        if (isset($data['shipping']['address_id']) && $data['shipping']['address_id']) {
            $customerAddress = $this
                ->customerAddressRepository
                ->findOneWhere(['id' => $data['shipping']['address_id']])
                ->toArray();
        }

        $shippingAddress = array_merge(
            $customerAddress,
            $data['shipping'],
            ['cart_id' => $cart->id],
            $this->fillCustomerAttributes(),
            $this->fillAddressAttributes($data['shipping'])
        );

        return $shippingAddress;
    }

    /**
     * Link addresses.
     *
     * @param  \Webkul\Checkout\Cart|null  $cart
     * @param  array  $billingAddressData
     * @param  array  $shippingAddressData
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    private function linkAddresses(
        \Webkul\Checkout\Models\Cart $cart,
        array $billingAddressData,
        array $shippingAddressData
    ): void {
        $billingAddressModel = $cart->billing_address;
        if ($billingAddressModel) {
            $billingAddressData['address_type'] = CartAddress::ADDRESS_TYPE_BILLING;
            $this->cartAddressRepository->update($billingAddressData, $billingAddressModel->id);

            if ($cart->haveStockableItems()) {
                $shippingAddressModel = $cart->shipping_address;
                if ($shippingAddressModel) {
                    if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                        $billingAddressData['address_type'] = CartAddress::ADDRESS_TYPE_SHIPPING;
                        $this->cartAddressRepository->update($billingAddressData, $shippingAddressModel->id);
                    } else {
                        $shippingAddressData['address_type'] = CartAddress::ADDRESS_TYPE_SHIPPING;
                        $this->cartAddressRepository->update($shippingAddressData, $shippingAddressModel->id);
                    }
                } else {
                    if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                        $this->cartAddressRepository->create(array_merge(
                            $billingAddressData,
                            ['address_type' => CartAddress::ADDRESS_TYPE_SHIPPING]
                        ));
                    } else {
                        $this->cartAddressRepository->create(array_merge(
                            $shippingAddressData,
                            ['address_type' => CartAddress::ADDRESS_TYPE_SHIPPING]
                        ));
                    }
                }
            }
        } else {
            $this->cartAddressRepository->create(array_merge($billingAddressData, ['address_type' => CartAddress::ADDRESS_TYPE_BILLING]));

            if ($cart->haveStockableItems()) {
                if (isset($billingAddressData['use_for_shipping']) && $billingAddressData['use_for_shipping']) {
                    $this->cartAddressRepository->create(array_merge($billingAddressData, ['address_type' => CartAddress::ADDRESS_TYPE_SHIPPING]));
                } else {
                    $this->cartAddressRepository->create(array_merge($shippingAddressData, ['address_type' => CartAddress::ADDRESS_TYPE_SHIPPING]));
                }
            }
        }
    }
}
