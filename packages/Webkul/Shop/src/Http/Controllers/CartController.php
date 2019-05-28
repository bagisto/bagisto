<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Illuminate\Support\Facades\Event;
use Cart;

/**
 * Cart controller for the customer and guest users for adding and
 * removing the products in the cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Protected Variables that holds instances of the repository classes.
     *
     * @param Array $_config
     * @param $cart
     * @param $cartItem
     * @param $customer
     * @param $product
     * @param $productView
     */
    protected $_config;

    protected $cart;

    protected $cartItem;

    protected $customer;

    protected $product;

    protected $suppressFlash = false;

    /**
     * WishlistRepository Repository object
     *
     * @var array
     */
    protected $wishlist;

    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CustomerRepository $customer,
        ProductRepository $product,
        WishlistRepository $wishlist
    )
    {

        $this->middleware('customer')->only(['moveToWishlist']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;

        $this->wishlist = $wishlist;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return Mixed
     */
    public function index()
    {
        return view($this->_config['view'])->with('cart', Cart::getCart());
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */
    public function add($id)
    {
        try {
            Event::fire('checkout.cart.add.before', $id);

            $result = Cart::add($id, request()->except('_token'));

            Event::fire('checkout.cart.add.after', $result);

            Cart::collectTotals();

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if (auth()->guard('customer')->user()) {
                    $customer = auth()->guard('customer')->user();

                    if (count($customer->wishlist_items)) {
                        foreach ($customer->wishlist_items as $wishlist) {
                            if ($wishlist->product_id == $id) {
                                $this->wishlist->delete($wishlist->id);
                            }
                        }
                    }
                }

                return redirect()->back();
            } else {
                session()->flash('warning', trans('shop::app.checkout.cart.item.error-add'));

                return redirect()->back();
            }

            return redirect()->route($this->_config['redirect']);

        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param integer $itemId
     */
    public function remove($itemId)
    {
        Event::fire('checkout.cart.delete.before', $itemId);

        Cart::removeItem($itemId);

        Event::fire('checkout.cart.delete.after', $itemId);

        Cart::collectTotals();

        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return response
     */
    public function updateBeforeCheckout()
    {
        $request = request()->except('_token');

        foreach ($request['qty'] as $id => $quantity) {
            if ($quantity <= 0) {
                session()->flash('warning', trans('shop::app.checkout.cart.quantity.illegal'));

                return redirect()->back();
            }
        }

        foreach ($request['qty'] as $key => $value) {
            $item = $this->cartItem->findOneByField('id', $key);

            $data['quantity'] = $value;

            Event::fire('checkout.cart.update.before', $key);

            $result = Cart::updateItem($item->product_id, $data, $key);

            if ($result == false) {
                $this->suppressFlash = true;
            }

            Event::fire('checkout.cart.update.after', $item);

            unset($item);
            unset($data);
        }

        Cart::collectTotals();

        if ($this->suppressFlash) {
            session()->forget('success');
            session()->forget('warning');
            session()->flash('info', trans('shop::app.checkout.cart.partial-cart-update'));
        }

        return redirect()->back();
    }

    /**
     * Add the configurable product
     * to the cart.
     *
     * @return response
     */
    public function addConfigurable($slug)
    {
        session()->flash('warning', trans('shop::app.checkout.cart.add-config-warning'));
        return redirect()->route('shop.products.index', $slug);
    }

    public function buyNow($id)
    {
        Event::fire('checkout.cart.add.before', $id);

        $result = Cart::proceedToBuyNow($id);

        Event::fire('checkout.cart.add.after', $result);

        Cart::collectTotals();

        if (! $result) {
            return redirect()->back();
        } else {
            return redirect()->route('shop.checkout.onepage.index');
        }
    }

    /**
     * Function to move a already added product to wishlist
     * will run only on customer authentication.
     *
     * @param instance cartItem $id
     */
    public function moveToWishlist($id)
    {
        $result = Cart::moveToWishlist($id);

        if (! $result) {
            Cart::collectTotals();

            session()->flash('success', trans('shop::app.wishlist.moved'));

            return redirect()->back();
        } else {
            session()->flash('warning', trans('shop::app.wishlist.move-error'));

            return redirect()->back();
        }
    }

    /**
     * To apply coupon rules
     */
    public function applyCoupons()
    {
        $this->validate(request(), [
            'code' => 'string|required'
        ]);

        $code = request()->input('code');
        $rules = Cart::setCoupon();
        $appliedRule = null;
        $coupons = [];

        foreach($rules['id'] as $rule) {
            array_push($coupons, $rule->coupons->code);
            if ($rule->use_coupon && $rule->auto_generation == 0) {
                if ($rule->coupons->code == $code) {
                    $appliedRule = $rule;

                    break;
                } else {
                    continue;
                }
            }
        }

        if (! isset($appliedRule)) {
            return response()->json(['message' => trans('admin::app.promotion.status.no-coupon')], 200);
        }

        $cart = Cart::getCart();

        //all of conditions is/are true
        $result = null;
        if ($appliedRule->conditions) {
            $conditions = json_decode(json_decode($appliedRule->conditions));

            $shipping_address = $cart->getShippingAddressAttribute();

            $shipping_method = $cart->shipping_method;
            $shipping_country = $shipping_address->country;
            $shipping_state = $shipping_address->state;
            $shipping_postcode = $shipping_address->postcode;
            $shipping_city = $shipping_address->city;

            $payment_method = $cart->payment->method;
            $sub_total = $cart->base_sub_total;

            $total_items = $cart->items_qty;
            $total_weight = 0;

            foreach($cart->items as $item) {
                $total_weight = $total_weight + $item->base_total_weight;
            }

            $test_mode = config('pricerules.test_mode.0');
            $test_conditions = config('pricerules.cart.conditions');

            if ($test_mode == config('pricerules.test_mode.0')) {
                $result = $this->testAllConditionAreTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.1')) {
                $result = $this->testAllConditionAreFalse($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.2')) {
                $result = $this->testAnyConditionIsTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.3')) {
                $result = $this->testAbyConditionIsFalse($conditions, $cart);
            }
        }

        // check all the conditions associated with the rule
        if ($result) {
            // if (isset($appliedRule) && $appliedRule->starts_from == null) {
            $action_type = $appliedRule->action_type;
            $disc_threshold = $appliedRule->disc_threshold;
            $disc_amount = $appliedRule->disc_amount;
            $disc_quantity = $appliedRule->disc_quantity;

            $newBaseSubTotal = 0;
            $newQuantity = 0;

            if ($cart->items_qty >= $disc_threshold) {
                // add the time conditions if rule is expired and active then make it in active
                $leastWorthItem = Cart::leastWorthItem();

                if ($action_type == config('pricerules.cart.validation.0')) {
                    $newBaseSubTotal = ($leastWorthItem['base_total'] * $disc_amount) / 100;
                } else if ($action_type == config('pricerules.cart.validation.1')) {
                    $newBaseSubTotal = $leastWorthItem['base_total'] - $disc_amount;
                } else if ($action_type == config('pricerules.cart.validation.2')) {
                    $newQuantity = $this->cartItem->find($leastWorthItem['id'])->quantity + $disc_amount;
                } else if ($action_type == config('pricerules.cart.validation.3')) {
                    $base_total = $disc_amount;
                }

                if ($action_type == config('pricerules.cart.validation.2')) {
                    return response()->json([
                        'message' => 'Success',
                        'action' => $action_type,
                        'amount_given' => false,
                        'amount' => $newQuantity
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Success',
                        'action' => $action_type,
                        'amount_given' => true,
                        'amount' => $newBaseSubTotal
                    ]);
                }
            } else {
                return response()->json([
                    'message' => 'failed',
                    'action' => $action_type,
                    'amount_given' => null,
                    'amount' => null,
                    'least_value_item' => null
                ]);
            }
            // } else if (isset($appliedRule) && $appliedRule->start_from != null) {
            //     //time based rules
            //     $action_type = $appliedRule->action_type;
            //     $disc_threshold = $appliedRule->disc_threshold;
            //     $disc_amount = $appliedRule->disc_amount;
            //     $disc_quantity = $appliedRule->disc_quantity;

            //     $newBaseSubTotal = 0;
            //     $newQuantity = 0;
            //     if ($cart->items_qty >= $disc_threshold) {
            //         $leastWorthItem = Cart::leastWorthItem();

            //         if ($action_type == config('pricerules.cart.validation.0')) {
            //             $newBaseSubTotal = ($leastWorthItem['base_total'] * $disc_amount) / 100;
            //         } else if ($action_type == config('pricerules.cart.validation.1')) {
            //             $newBaseSubTotal = $leastWorthItem['base_total'] - $disc_amount;
            //         } else if ($action_type == config('pricerules.cart.validation.2')) {
            //             $newQuantity = $this->cartItem->find($leastWorthItem['id'])->quantity + $disc_amount;
            //         } else if ($action_type == config('pricerules.cart.validation.3')) {
            //             $base_total = $disc_amount;
            //         }

            //         if ($action_type == config('pricerules.cart.validation.2')) {
            //             return response()->json([
            //                 'message' => 'Success',
            //                 'action' => $action_type,
            //                 'amount_given' => false,
            //                 'amount' => $newQuantity
            //             ]);
            //         } else {
            //             return response()->json([
            //                 'message' => 'Success',
            //                 'action' => $action_type,
            //                 'amount_given' => true,
            //                 'amount' => $newBaseSubTotal
            //             ]);
            //         }
            //     } else {
            //         return response()->json([
            //             'message' => 'failed',
            //             'action' => $action_type,
            //             'amount_given' => null,
            //             'amount' => null,
            //             'least_value_item' => Cart::leastWorthItem()
            //         ]);
            //     }
            // }
        } else {
            return response()->json([
                'message' => 'failed',
                'action' => null,
                'amount_given' => null,
                'amount' => null,
                'least_value_item' => null
            ]);
        }
    }

    protected function testAllConditionAreTrue($conditions, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');

        $result = 1;
        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (! ($actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                }
            }
                // else if ($condition->type == 'boolean') {
                // if ($test_conditions[$condition->type][$test_condition]) {
                //     if ($test_condition == 0) {
                //         dd($test_condition);
                //     } else if ($test_condition == 1) {
                //         dd($test_condition);
                //     }
                // }
                // }
        }

        return $result;
    }

    protected function testAllConditionAreFalse($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($test_condition == '=') {
                    if (! ($actual_value != $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (($actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                }
            }
                // else if ($condition->type == 'boolean') {
                // if ($test_conditions[$condition->type][$test_condition]) {
                //     if ($test_condition == 0) {
                //         dd($test_condition);
                //     } else if ($test_condition == 1) {
                //         dd($test_condition);
                //     }
                // }
                // }
        }

        return $result;
    }

    protected function testAnyConditionIsTrue($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text'){
                if ($test_condition == '=') {
                    if ($actual_value == $test_value) {
                        break;
                    }
                } else if ($test_condition == '>=') {
                    if ($actual_value >= $test_value) {
                        break;
                    }
                } else if ($test_condition == '<=') {
                    if ($actual_value <= $test_value) {
                        break;
                    }
                } else if ($test_condition == '>') {
                    if ($actual_value > $test_value) {
                        break;
                    }
                } else if ($test_condition == '<') {
                    if ($actual_value < $test_value) {
                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (str_contains($actual_value, $test_value)) {
                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! (! str_contains($actual_value, $test_value))) {
                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                } else {
                    $result = 0;
                }
            }
        }

        return $result;
    }

    protected function testAnyConditionIsFalse($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text'){
                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if ((! $actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! (str_contains($actual_value, $test_value))) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! (! str_contains($actual_value, $test_value))) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                } else {
                    $result = 0;
                }
            }
        }

        return $result;
    }
}