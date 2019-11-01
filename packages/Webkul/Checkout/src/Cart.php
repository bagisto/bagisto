<?php

namespace Webkul\Checkout;

use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Illuminate\Support\Facades\Event;

/**
 * Facades handler for all the methods to be implemented in Cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart {

    /**
     * CartRepository instance
     *
     * @var mixed
     */
    protected $cartRepository;

    /**
     * CartItemRepository instance
     *
     * @var mixed
     */
    protected $cartItemRepository;

    /**
     * CartAddressRepository instance
     *
     * @var mixed
     */
    protected $cartAddressRepository;

    /**
     * ProductRepository instance
     *
     * @var mixed
     */
    protected $productRepository;

    /**
     * TaxCategoryRepository instance
     *
     * @var mixed
     */
    protected $taxCategoryRepository;

    /**
     * WishlistRepository instance
     *
     * @var mixed
     */
    protected $wishlistRepository;

    /**
     * CustomerAddressRepository instance
     *
     * @var mixed
     */
    protected $customerAddressRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Checkout\Repositories\CartRepository           $cart
     * @param  Webkul\Checkout\Repositories\CartItemRepository       $cartItem
     * @param  Webkul\Checkout\Repositories\CartAddressRepository    $cartAddress
     * @param  Webkul\Product\Repositories\ProductRepository         $product
     * @param  Webkul\Product\Repositories\TaxCategoryRepository     $taxCategory
     * @param  Webkul\Product\Repositories\CustomerAddressRepository $customerAddress
     * @param  Webkul\Product\Repositories\CustomerAddressRepository $customerAddress
     * @param  Webkul\Discount\Repositories\CartRuleRepository       $cartRule
     * @param  Webkul\Helpers\Discount                               $discount
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
    )
    {
        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;

        $this->cartAddressRepository = $cartAddressRepository;

        $this->productRepository = $productRepository;

        $this->taxCategoryRepository = $taxCategoryRepository;

        $this->wishlistRepository = $wishlistRepository;

        $this->customerAddressRepository = $customerAddressRepository;
    }

    /**
     * Return current logged in customer
     *
     * @return Customer|boolean
     */
    public function getCurrentCustomer()
    {
        $guard = request()->has('token') ? 'api' : 'customer';

        return auth()->guard($guard);
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param integer $productId
     * @param array   $data
     * @return Cart
     */
    public function addProduct($productId, $data)
    {
        Event::fire('checkout.cart.add.before', $productId);

        $cart = $this->getCart();

        if (! $cart && ! $cart = $this->create($data))
            return;

        $product = $this->productRepository->findOneByField('id', $productId);

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            $this->collectTotals();

            throw new \Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct);

                if (isset($cartProduct['parent_id']))
                    $cartProduct['parent_id'] = $parentCartItem->id;

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (isset($cartProduct['parent_id']) && $cartItem->parent_id != $parentCartItem->id) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem)
                    $parentCartItem = $cartItem;
            }
        }

        Event::fire('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->getCart();
    }

    /**
     * Create new cart instance.
     *
     * @param array $data
     * @return Cart|null
     */
    public function create($data)
    {
        $cartData = [
            'channel_id' => core()->getCurrentChannel()->id,
            'global_currency_code' => core()->getBaseCurrencyCode(),
            'base_currency_code' => core()->getBaseCurrencyCode(),
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code' => core()->getCurrentCurrencyCode(),
            'items_count' => 1
        ];

        //Authentication details
        if ($this->getCurrentCustomer()->check()) {
            $cartData['customer_id'] = $this->getCurrentCustomer()->user()->id;
            $cartData['is_guest'] = 0;
            $cartData['customer_first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $cartData['customer_last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $cartData['customer_email'] = $this->getCurrentCustomer()->user()->email;
        } else {
            $cartData['is_guest'] = 1;
        }

        $cart = $this->cartRepository->create($cartData);

        if (! $cart) {
            session()->flash('error', trans('shop::app.checkout.cart.create-error'));

            return;
        }

        $this->putCart($cart);

        return $cart;
    }

    /**
     * Update cart items information
     *
     * @param array $data
     *
     * @return string|boolean
     */
    public function updateItems($data)
    {
        foreach ($data['qty'] as $itemId => $quantity) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);
        
            if (! $item)
                continue;

            if ($quantity <= 0) {
                $this->removeItem($itemId);

                throw new \Exception(trans('shop::app.checkout.cart.quantity.illegal'));
            }

            $item->quantity = $quantity;

            if (! $this->isItemHaveQuantity($item))
                throw new \Exception(trans('shop::app.checkout.cart.quantity.inventory_warning'));

            Event::fire('checkout.cart.update.before', $item);

            $this->cartItemRepository->update([
                    'quantity' => $quantity,
                    'total' => core()->convertPrice($item->price * $quantity),
                    'base_total' => $item->price * $quantity,
                    'total_weight' => $item->weight * $quantity,
                    'base_total_weight' => $item->weight * $quantity
                ], $itemId);

            Event::fire('checkout.cart.update.after', $item);
        }

        $this->collectTotals();

        return true;
    }

    /**
     * Get cart item by product
     *
     * @param array $data
     * @return CartItem|void
     */
    public function getItemByProduct($data)
    {
        $items = $this->getCart()->all_items;

        foreach ($items as $item) {
            if ($item->product->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (isset($data['additional']['parent_id'])) {
                    if ($item->parent->product->getTypeInstance()->compareOptions($item->parent->additional, request()->all()))
                        return $item;
                } else {
                    return $item;
                }
            }
        }
    }

    /**
     * Remove the item from the cart
     *
     * @param integer $itemId
     * @return boolean
     */
    public function removeItem($itemId)
    {
        Event::fire('checkout.cart.delete.before', $itemId);

        if (! $cart = $this->getCart())
            return false;

        $this->cartItemRepository->delete($itemId);

        //delete the cart instance if no items are there
        if ($cart->items()->get()->count() == 0) {
            $this->cartRepository->delete($cart->id);

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }

        Event::fire('checkout.cart.delete.after', $itemId);

        $this->collectTotals();

        return true;
    }

    /**
     * This function handles when guest has some of cart products and then logs in.
     *
     * @return boolean
     */
    public function mergeCart()
    {
        if (session()->has('cart')) {
            $cart = $this->cartRepository->findOneWhere(['customer_id' => $this->getCurrentCustomer()->user()->id, 'is_active' => 1]);

            $guestCart = session()->get('cart');

            //when the logged in customer is not having any of the cart instance previously and are active.
            if (! $cart) {
                $this->cartRepository->update([
                    'customer_id' => $this->getCurrentCustomer()->user()->id,
                    'is_guest' => 0,
                    'customer_first_name' => $this->getCurrentCustomer()->user()->first_name,
                    'customer_last_name' => $this->getCurrentCustomer()->user()->last_name,
                    'customer_email' => $this->getCurrentCustomer()->user()->email
                ], $guestCart->id);

                session()->forget('cart');

                return true;
            }

            foreach ($guestCart->items as $key => $guestCartItem) {

                $found = false;
                
                foreach ($cart->items as $cartItem) {
                    if (! $cartItem->product->getTypeInstance()->compareOptions($cartItem->additional, $guestCartItem->additional))
                        continue;

                    $cartItem->quantity = $newQuantity = $cartItem->quantity + $guestCartItem->quantity;

                    if (! $this->isItemHaveQuantity($cartItem)) {
                        $this->cartItemRepository->delete($guestCartItem->id);

                        continue;
                    }

                    $this->cartItemRepository->update([
                        'quantity' => $newQuantity,
                        'total' => core()->convertPrice($cartItem->price * $newQuantity),
                        'base_total' => $cartItem->price * $newQuantity,
                        'total_weight' => $cartItem->weight * $newQuantity,
                        'base_total_weight' => $cartItem->weight * $newQuantity
                    ], $cartItem->id);

                    $guestCart->items->forget($key);

                    $this->cartItemRepository->delete($guestCartItem->id);

                    $found = true;
                }

                if (! $found) {
                    $this->cartItemRepository->update([
                        'cart_id' => $cart->id
                    ], $guestCartItem->id);

                    foreach ($guestCartItem->children as $child) {
                        $this->cartItemRepository->update([
                            'cart_id' => $cart->id
                        ], $child->id);
                    }
                }
            }

            $this->collectTotals();

            $this->cartRepository->delete($guestCart->id);

            session()->forget('cart');
        }

        return true;
    }

    /**
     * Save cart
     *
     * @param Cart $cart
     * @return void
     */
    public function putCart($cart)
    {
        if (! $this->getCurrentCustomer()->check()) {
            session()->put('cart', $cart);
        }
    }

    /**
     * Returns cart
     *
     * @return Cart|null
     */
    public function getCart()
    {
        $cart = null;

        if ($this->getCurrentCustomer()->check()) {
            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => $this->getCurrentCustomer()->user()->id,
                'is_active' => 1
            ]);

        } elseif (session()->has('cart')) {
            $cart = $this->cartRepository->find(session()->get('cart')->id);
        }

        return $cart && $cart->is_active ? $cart : null;
    }

    /**
     * Returns cart details in array
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

            $data['selected_shipping_rate'] = $cart->selected_shipping_rate->toArray();
        }

        $data['payment'] = $cart->payment->toArray();

        $data['items'] = $cart->items->toArray();

        return $data;
    }

    /**
     * Save customer address
     *
     * @param array $data
     * @return boolean
     */
    public function saveCustomerAddress($data)
    {
        if (! $cart = $this->getCart())
            return false;

        $billingAddress = $data['billing'];
        $billingAddress['cart_id'] = $cart->id;

        if (isset($data['billing']['address_id']) && $data['billing']['address_id']) {
            $address = $this->customerAddressRepository->findOneWhere(['id'=> $data['billing']['address_id']])->toArray();

            $billingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $billingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $billingAddress['email'] = $this->getCurrentCustomer()->user()->email;
            $billingAddress['address1'] = $address['address1'];
            $billingAddress['country'] = $address['country'];
            $billingAddress['state'] = $address['state'];
            $billingAddress['city'] = $address['city'];
            $billingAddress['postcode'] = $address['postcode'];
            $billingAddress['phone'] = $address['phone'];
        }

        if (isset($data['billing']['save_as_address']) && $data['billing']['save_as_address']) {
            $billingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;
            $this->customerAddressRepository->create($billingAddress);
        }

        if ($cart->haveStockableItems()) {
            $shippingAddress = $data['shipping'];
            $shippingAddress['cart_id'] = $cart->id;
            
            if (isset($data['shipping']['address_id']) && $data['shipping']['address_id']) {
                $address = $this->customerAddressRepository->findOneWhere(['id'=> $data['shipping']['address_id']])->toArray();

                $shippingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
                $shippingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
                $shippingAddress['email'] = $this->getCurrentCustomer()->user()->email;
                $shippingAddress['address1'] = $address['address1'];
                $shippingAddress['country'] = $address['country'];
                $shippingAddress['state'] = $address['state'];
                $shippingAddress['city'] = $address['city'];
                $shippingAddress['postcode'] = $address['postcode'];
                $shippingAddress['phone'] = $address['phone'];
            }

            if (isset($data['shipping']['save_as_address']) && $data['shipping']['save_as_address']) {
                $shippingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;

                $this->customerAddressRepository->create($shippingAddress);
            }
        }

        if ($billingAddressModel = $cart->billing_address) {
            $this->cartAddressRepository->update($billingAddress, $billingAddressModel->id);

            if ($cart->haveStockableItems()) {
                if ($shippingAddressModel = $cart->shipping_address) {
                    if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                        $this->cartAddressRepository->update($billingAddress, $shippingAddressModel->id);
                    } else {
                        $this->cartAddressRepository->update($shippingAddress, $shippingAddressModel->id);
                    }
                } else {
                    if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                        $this->cartAddressRepository->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                    } else {
                        $this->cartAddressRepository->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                    }
                }
            }
        } else {
            $this->cartAddressRepository->create(array_merge($billingAddress, ['address_type' => 'billing']));

            if ($cart->haveStockableItems()) {
                if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddressRepository->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddressRepository->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                }
            }
        }

        if ($this->getCurrentCustomer()->check()) {
            $cart->customer_email = $this->getCurrentCustomer()->user()->email;
            $cart->customer_first_name = $this->getCurrentCustomer()->user()->first_name;
            $cart->customer_last_name = $this->getCurrentCustomer()->user()->last_name;
        } else {
            $cart->customer_email = $cart->billing_address->email;
            $cart->customer_first_name = $cart->billing_address->first_name;
            $cart->customer_last_name = $cart->billing_address->last_name;
        }

        $cart->save();

        return true;
    }

    /**
     * Save shipping method for cart
     *
     * @param string $shippingMethodCode
     * @return boolean
     */
    public function saveShippingMethod($shippingMethodCode)
    {
        if (! $cart = $this->getCart())
            return false;

        $cart->shipping_method = $shippingMethodCode;
        $cart->save();

        return true;
    }

    /**
     * Save payment method for cart
     *
     * @param string $payment
     * @return CartPayment
     */
    public function savePaymentMethod($payment)
    {
        if (! $cart = $this->getCart())
            return false;

        if ($cartPayment = $cart->payment)
            $cartPayment->delete();

        $cartPayment = new CartPayment;

        $cartPayment->method = $payment['method'];
        $cartPayment->cart_id = $cart->id;
        $cartPayment->save();

        return $cartPayment;
    }

    /**
     * Updates cart totals
     *
     * @return void
     */
    public function collectTotals()
    {
        $validated = $this->validateItems();

        if (! $validated)
            return false;

        if (! $cart = $this->getCart())
            return false;

        $this->calculateItemsTax();

        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->tax_total = $cart->base_tax_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;

        foreach ($cart->items()->get() as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;

            $cart->grand_total = (float) $cart->grand_total + $item->total + $item->tax_amount - $item->discount_amount;
            $cart->base_grand_total = (float) $cart->base_grand_total + $item->base_total + $item->base_tax_amount - $item->base_discount_amount;

            $cart->sub_total = (float) $cart->sub_total + $item->total;
            $cart->base_sub_total = (float) $cart->base_sub_total + $item->base_total;

            $cart->tax_total = (float) $cart->tax_total + $item->tax_amount;
            $cart->base_tax_total = (float) $cart->base_tax_total + $item->base_tax_amount;
        }

        if ($shipping = $cart->selected_shipping_rate) {
            $cart->grand_total = (float) $cart->grand_total + $shipping->price;
            $cart->base_grand_total = (float) $cart->base_grand_total + $shipping->base_price;
        }

        $quantities = 0;
        foreach ($cart->items as $item) {
            $quantities = $quantities + $item->quantity;
        }

        $cart->items_count = $cart->items->count();

        $cart->items_qty = $quantities;

        $cart->save();
    }

    /**
     * To validate if the product information is changed by admin and the items have
     * been added to the cart before it.
     *
     * @return boolean
     */
    public function validateItems()
    {
        if (! $cart = $this->getCart())
            return;

        //rare case of accident-->used when there are no items.
        if (count($cart->items) == 0) {
            $this->cartRepository->delete($cart->id);
            
            return false;
        } else {
            foreach ($cart->items as $item) {
                $price = ! is_null($item->custom_price) ? $item->custom_price : $item->base_price;

                $this->cartItemRepository->update([
                    'price' => core()->convertPrice($price),
                    'base_price' => $price,
                    'total' => core()->convertPrice($price * $item->quantity),
                    'base_total' => $price * $item->quantity,
                ], $item->id);
            }

            return true;
        }
    }

    /**
     * Calculates cart items tax
     *
     * @return void
     */
    public function calculateItemsTax()
    {
        if (! $cart = $this->getCart())
            return false;

        if (! $cart->shipping_address && ! $cart->billing_address)
            return;

        foreach ($cart->items()->get() as $item) {
            $taxCategory = $this->taxCategoryRepository->find($item->product->tax_category_id);

            if (! $taxCategory)
                continue;
            
            if ($item->product->getTypeInstance()->isStockable()) {
                $address = $cart->shipping_address;
            } else {
                $address = $cart->billing_address;
            }

            $taxRates = $taxCategory->tax_rates()->where([
                    'state' => $address->state,
                    'country' => $address->country,
                ])->orderBy('tax_rate', 'desc')->get();

            if (count( $taxRates) > 0) {
                foreach ($taxRates as $rate) {
                    $haveTaxRate = false;

                    if (! $rate->is_zip) {
                        if ($rate->zip_code == '*' || $rate->zip_code == $address->postcode) {
                            $haveTaxRate = true;
                        }
                    } else {
                        if ($address->postcode >= $rate->zip_from && $address->postcode <= $rate->zip_to) {
                            $haveTaxRate = true;
                        }
                    }

                    if ($haveTaxRate) {
                        $item->tax_percent = $rate->tax_rate;
                        $item->tax_amount = ($item->total * $rate->tax_rate) / 100;
                        $item->base_tax_amount = ($item->base_total * $rate->tax_rate) / 100;

                        $item->save();
                        break;
                    }
                }
            } else {
                $item->tax_percent = 0;
                $item->tax_amount = 0;
                $item->base_tax_amount = 0;

                $item->save();
            }
        }
    }

    /**
     * Checks if cart has any error
     *
     * @return boolean
     */
    public function hasError()
    {
        if (! $this->getCart())
            return true;

        if (! $this->isItemsHaveSufficientQuantity())
            return true;

        return false;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @return boolean
     */
    public function isItemsHaveSufficientQuantity()
    {
        foreach ($this->getCart()->items as $item) {
            if (! $this->isItemHaveQuantity($item))
                return false;
        }

        return true;
    }

    /**
     * Checks if all cart items have sufficient quantity.
     *
     * @param CartItem $item
     * @return boolean
     */
    public function isItemHaveQuantity($item)
    {
        return $item->product->getTypeInstance()->isItemHaveQuantity($item);
    }

    /**
     * Deactivates current cart
     *
     * @return void
     */
    public function deActivateCart()
    {
        if ($cart = $this->getCart()) {
            $this->cartRepository->update(['is_active' => false], $cart->id);

            if (session()->has('cart')) {
                session()->forget('cart');
            }
        }
    }

    /**
     * Validate order before creation
     *
     * @return array
     */
    public function prepareDataForOrder()
    {
        $data = $this->toArray();

        $finalData = [
            'cart_id' => $this->getCart()->id,
            'customer_id' => $data['customer_id'],
            'is_guest' => $data['is_guest'],
            'customer_email' => $data['customer_email'],
            'customer_first_name' => $data['customer_first_name'],
            'customer_last_name' => $data['customer_last_name'],
            'customer' => $this->getCurrentCustomer()->check() ? $this->getCurrentCustomer()->user() : null,
            'total_item_count' => $data['items_count'],
            'total_qty_ordered' => $data['items_qty'],
            'base_currency_code' => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code' => $data['cart_currency_code'],
            'grand_total' => $data['grand_total'],
            'base_grand_total' => $data['base_grand_total'],
            'sub_total' => $data['sub_total'],
            'base_sub_total' => $data['base_sub_total'],
            'tax_amount' => $data['tax_total'],
            'base_tax_amount' => $data['base_tax_total'],
            'discount_amount' => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            'billing_address' => array_except($data['billing_address'], ['id', 'cart_id']),
            'payment' => array_except($data['payment'], ['id', 'cart_id']),
            'channel' => core()->getCurrentChannel(),
        ];

        if ($this->getCart()->haveStockableItems()) {
            $finalData = array_merge($finalData, [
                'shipping_method' => $data['selected_shipping_rate']['method'],
                'shipping_title' => $data['selected_shipping_rate']['carrier_title'] . ' - ' . $data['selected_shipping_rate']['method_title'],
                'shipping_description' => $data['selected_shipping_rate']['method_description'],
                'shipping_amount' => $data['selected_shipping_rate']['price'],
                'base_shipping_amount' => $data['selected_shipping_rate']['base_price'],
                'shipping_address' => array_except($data['shipping_address'], ['id', 'cart_id']),
            ]);
        }

        foreach ($data['items'] as $item) {
            $finalData['items'][] = $this->prepareDataForOrderItem($item);
        }

        return $finalData;
    }

    /**
     * Prepares data for order item
     *
     * @param array $data
     * @return array
     */
    public function prepareDataForOrderItem($data)
    {
        $finalData = [
            'product' => $this->productRepository->find($data['product_id']),
            'sku' => $data['sku'],
            'type' => $data['type'],
            'name' => $data['name'],
            'weight' => $data['weight'],
            'total_weight' => $data['total_weight'],
            'qty_ordered' => $data['quantity'],
            'price' => $data['price'],
            'base_price' => $data['base_price'],
            'total' => $data['total'],
            'base_total' => $data['base_total'],
            'tax_percent' => $data['tax_percent'],
            'tax_amount' => $data['tax_amount'],
            'base_tax_amount' => $data['base_tax_amount'],
            'discount_percent' => $data['discount_percent'],
            'discount_amount' => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            'additional' => $data['additional'],
        ];

        if (isset($data['children']) && $data['children']) {
            foreach ($data['children'] as $child) {
                $child['quantity'] = $child['quantity'] ? $child['quantity'] * $data['quantity'] : $child['quantity'];

                $finalData['children'][] = $this->prepareDataForOrderItem($child);
            }
        }

        return $finalData;
    }

    /**
     * Move a wishlist item to cart
     * 
     * @param WishlistItem $wishlistItem
     * @return boolean
     */
    public function moveToCart($wishlistItem)
    {
        if (! $wishlistItem->product->getTypeInstance()->canBeMovedFromWishlistToCart($wishlistItem))
            return false;
    
        if (! $wishlistItem->additional)
            $wishlistItem->additional = ['product_id' => $wishlistItem->product_id];

        request()->merge($wishlistItem->additional);

        $result = $this->addProduct($wishlistItem->product_id, $wishlistItem->additional);

        if ($result) {
            $this->wishlistRepository->delete($wishlistItem->id);

            return true;
        }

        return false;
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param integer $itemId
     * @return boolean|void
     */
    public function moveToWishlist($itemId)
    {
        $cart = $this->getCart();

        $cartItem = $cart->items()->find($itemId);

        if (! $cartItem)
            return false;

        $wishlistItems = $this->wishlistRepository->findWhere([
                'customer_id' => $this->getCurrentCustomer()->user()->id,
                'product_id' => $cartItem->product_id
            ]);

        $found = false;

        foreach ($wishlistItems as $wishlistItem) {
            if ($cartItem->product->getTypeInstance()->compareOptions($cartItem->additional, $wishlistItem->item_options))
                $found = true;
        }

        if (! $found) {
            $this->wishlistRepository->create([
                    'channel_id' => $cart->channel_id,
                    'customer_id' => $this->getCurrentCustomer()->user()->id,
                    'product_id' => $cartItem->product_id,
                    'additional' => $cartItem->additional
                ]);
        }

        $result = $this->cartItemRepository->delete($itemId);

        if (! $cart->items()->count())
            $this->cartRepository->delete($cart->id);

        $this->collectTotals();

        return true;
    }
}