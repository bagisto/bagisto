<?php

namespace Webkul\Cart;

use Carbon\Carbon;
use Webkul\Cart\Repositories\CartRepository;
use Webkul\Cart\Repositories\CartItemRepository;
use Webkul\Cart\Repositories\CartAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductRepository;
use Cookie;

/**
 * Facade for all the methods to be implemented in Cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart {

    /**
     * CartRepository model
     *
     * @var mixed
     */
    protected $cart;

    /**
     * CartItemRepository model
     *
     * @var mixed
     */
    protected $cartItem;

    /**
     * CustomerRepository model
     *
     * @var mixed
     */
    protected $customer;

    /**
     * CartAddressRepository model
     *
     * @var mixed
     */
    protected $cartAddress;

    /**
     * ProductRepository model
     *
     * @var mixed
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Cart\Repositories\CartRepository $cart
     * @param  Webkul\Cart\Repositories\CartItemRepository $cartItem
     * @param  Webkul\Cart\Repositories\CartAddressRepository $cartAddress
     * @param  Webkul\Customer\Repositories\CustomerRepository $customer
     * @param  Webkul\Product\Repositories\ProductRepository $product
     * @return void
     */
    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CartAddressRepository $cartAddress,
        CustomerRepository $customer,
        ProductRepository $product)
    {
        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->cartAddress = $cartAddress;

        $this->product = $product;
    }

     /**
     * Method to check if the product is available and its required quantity
     * is available or not in the inventory sources.
     *
     * @param integer $id
     *
     * @return Array
     */
    public function canCheckOut($id) {
        $cart = $this->cart->findOneByField('id', 144);

        $items = $cart->items;

        $allProdQty = array();

        $allProdQty1 = array();

        $totalQty = 0;

        foreach($items as $item) {
            $inventories = $item->product->inventories;

            $inventory_sources = $item->product->inventory_sources;

            $totalQty = 0;

            foreach($inventory_sources as $inventory_source) {
                if($inventory_source->status!=0) {
                    foreach($inventories as $inventory) {
                        $totalQty = $totalQty + $inventory->qty;
                    }

                    array_push($allProdQty1, $totalQty);

                    $allProdQty[$item->product->id] = $totalQty;
                }
            }
        }

        foreach ($items as $item) {
            $inventories = $item->product->inventory_sources->where('status', '=', '1');

            foreach($inventories as $inventory) {
                dump($inventory->status);
            }
        }

        dd($allProdQty);

        dd([true, false]);
    }

    /**
     * Create new cart instance with the current item added.
     *
     * @param integer $id
     * @param array $data
     *
     * @return Response
     */
    public function createNewCart($id, $data)
    {
        $itemData = $this->prepareItemData($id, $data);

        // dd($itemData);

        $cartData['channel_id'] = core()->getCurrentChannel()->id;

        // this will auto set the customer id for the cart instances if customer is authenticated
        if(auth()->guard('customer')->check()) {
            $cartData['customer_id'] = auth()->guard('customer')->user()->id;

            $cartData['is_guest'] = 1;

            $cartData['customer_full_name'] = auth()->guard('customer')->user()->first_name .' '. auth()->guard('customer')->user()->last_name;
        }

        $cartData['items_count'] = 1;

        $cartData['items_quantity'] = $data['quantity'];

        if($cart = $this->cart->create($cartData)) {
            $itemData['parent']['cart_id'] = $cart->id;

            if ($data['is_configurable'] == "true") {
                //parent product entry
                $itemData['parent']['additional'] = json_encode($data);
                if($parent = $this->cartItem->create($itemData['parent'])) {

                    $itemData['child']['parent_id'] = $parent->id;
                    if($child = $this->cartItem->create($itemData['child'])) {
                        session()->put('cart', $cart);

                        session()->flash('success', 'Item Added To Cart Successfully');

                        return redirect()->back();
                    }
                }

            } else if($data['is_configurable'] == "false") {
                if($result = $this->cartItem->create($itemData['parent'])) {
                    session()->put('cart', $cart);

                    session()->flash('success', 'Item Added To Cart Successfully');

                    return redirect()->back();
                }
            }
        }

        session()->flash('error', 'Some Error Occured');

        return redirect()->back();
    }

    /**
     * Prepare the other data for the product to be added.
     *
     * @param integer $id
     * @param array $data
     *
     * @return array
     */
    public function prepareItemData($id, $data) {
        unset($data['_token']);

        if(!isset($data['is_configurable']) || !isset($data['product']) ||!isset($data['quantity'])) {
            session()->flash('error', 'Cart System Integrity Violation, Some Required Fields Missing.');

            dd('discrepancy 1');

            return redirect()->back();
        } else {
            if($data['is_configurable'] == "true") {
                if(!isset($data['super_attribute'])) {
                    session()->flash('error', 'Cart System Integrity Violation, Configurable Options Not Found In Request.');

                    dd('discrepancy 2');

                    return redirect()->back();
                }
            }
        }

        $data['sku'] = $this->product->findOneByField('id', $data['product'])->sku;

        if(isset($data['is_configurable']) && $data['is_configurable'] == "true") {
            $parentData['sku'] = $data['sku'];

            $parentData['product_id'] = $id;

            $parentData['quantity'] = $data['quantity'];

            $parentData['type'] = 'configurable';

            $parentData['name'] = $this->product->findOneByField('id', $id)->name;

            $parentData['price'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->price;

            $parentData['base_price'] = $parentData['price'];

            $parentData['item_total'] = $parentData['price'] * $data['quantity'];

            $parentData['base_item_total'] = $parentData['price'] * $data['quantity'];

            $parentData['weight'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->weight;

            $parentData['item_weight'] = $parentData['weight'] * $parentData['quantity'];

            $parentData['base_item_weight'] = $parentData['weight'] * $parentData['quantity'];

            //child row data
            $childData['product_id'] = $data['selected_configurable_option'];

            $childData['quantity'] = 1;

            $childData['sku'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->sku;

            $childData['type'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->type;

            $childData['name'] = $this->product->findOneByField('id', $data['selected_configurable_option'])->name;

            return ['parent' => $parentData, 'child' => $childData];
        } else {
            $data['product_id'] = $id;
            unset($data['product']);

            $data['type'] = 'simple';

            $data['name'] = $this->product->findOneByField('id', $id)->name;

            $data['price'] = $this->product->findOneByField('id', $id)->price;

            $data['base_price'] = $data['price'];

            $data['item_total'] = $data['price'] * $data['quantity'];

            $data['base_item_total'] = $data['price'] * $data['quantity'];

            $data['weight'] = $this->product->findOneByField('id', $id)->weight;

            $data['item_weight'] = $data['weight'] * $data['quantity'];

            $data['base_item_weight'] = $data['weight'] * $data['quantity'];

            return ['parent' => $data, 'child' => null];
        }
    }

    /**
     * Add Items in a cart with some cart and item details.
     *
     * @param @id
     * @param $data
     *
     * @return void
     */
    public function add($id, $data)
    {
        $itemData = $this->prepareItemData($id, $data);

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items()->get();

            if(isset($cartItems)) {
                foreach($cartItems as $cartItem) {
                    if($data['is_configurable'] == "false") {
                        if($cartItem->product_id == $id) {
                            $prevQty = $cartItem->quantity;

                            $newQty = $data['quantity'];

                            $cartItem->update(['quantity' => $prevQty + $newQty]);

                            session()->flash('success', "Product Quantity Successfully Updated");

                            return redirect()->back();
                        }
                    } else if($data['is_configurable'] == "true") {
                        //check the parent and child records that holds info abt this product.
                        if($cartItem->product_id == $data['selected_configurable_option']) {
                            $child = $cartItem;

                            $parentId = $child->parent_id;

                            $parent = $this->cartItem->findOneByField('id', $parentId);

                            $parentPrice = $parent->price;

                            $prevQty = $parent->quantity;

                            $newQty = $data['quantity'];

                            $parent->update(['quantity' => $prevQty + $newQty, 'item_total' => $parentPrice * ($prevQty + $newQty)]);

                            session()->flash('success', "Product Quantity Successfully Updated");

                            return redirect()->back();
                        }
                    }
                }

                $parent = $cart->items()->create($itemData['parent']);

                $itemData['child']['parent_id'] = $parent->id;

                $cart->items()->create($itemData['child']);

                session()->flash('success', 'Item Successfully Added To Cart');

                return redirect()->back();
            } else {
                if(isset($cart)) {
                    $this->cart->delete($cart->id);
                } else {
                    $this->createNewCart($id, $data);
                }
            }
        } else {
            $this->createNewCart($id, $data);
        }
    }

    /**
     * Use detach to remove the current product from cart tables
     *
     * @param Integer $id
     * @return Mixed
     */
    public function remove($id)
    {

        dd("Removing Item from Cart");
    }

    /**
     * This function handles when guest has some of cart products and then logs in.
     *
     * @return Response
     */
    public function mergeCart()
    {
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            $cartItems = $cart->items;

            $customerCart = $this->cart->findOneByField('customer_id', auth()->guard('customer')->user()->id);

            if(isset($customerCart)) {
                $customerCartItems = $this->cart->items($customerCart['id']);

                if(isset($customerCart)) {
                    foreach($customerCartItems as $customerCartItem) {
                        if($customerCartItem->type == "simple" && isset($customerCartItem->parent_id)) {
                            //write the merge case whem the items exists with the customer cart also.
                            $child = $customerCartItem;

                            $parentId = $child->parent_id;

                            $parent = $this->cartItem->findOneByField('id', $parentId);

                            $parentPrice = $parent->price;

                            $prevQty = $parent->quantity;

                            foreach ($cartItems as $key => $cartItem) {
                                if ($cartItem->type == "simple" && isset($cartItem->parent_id)) {
                                    $newQty = $data['quantity'];

                                    $parent->update(['quantity' => $prevQty + $newQty, 'item_total' => $parentPrice * ($prevQty + $newQty)]);
                                } else if($cartItem->type == "simple" && is_null($cartItem->parent_id)){

                                }

                            }


                        } elseif($customerCartItem->type == "simple" && is_null($customerCartItem->parent_id)) {
                            foreach($cartItems as $key => $cartItem) {

                                if($cartItem->product_id == $customerCartItem->product_id) {

                                    $customerItemQuantity = $customerCartItem->quantity;

                                    $cartItemQuantity = $cartItem->quantity;

                                    $customerCartItem->update(['cart_id' => $customerCart->id, 'quantity' => $cartItemQuantity + $customerItemQuantity]);

                                    $this->cartItem->delete($cartItem->id);

                                    $cartItems->forget($key);
                                }
                            }
                        }
                    }

                    foreach($cartItems as $cartItem) {
                        $cartItem->update(['cart_id' => $customerCart->id]);
                    }

                    $this->cart->delete($cart->id);

                    return redirect()->back();
                }
            } else {
                foreach($cartItems as $cartItem) {
                    $this->cart->update(['customer_id' => auth()->guard('customer')->user()->id], $cart->id);
                }

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Returns cart
     *
     * @return Mixed
     */
    public function getCart()
    {
        if(!$cart = session()->get('cart'))
            return false;

        return $cart;
    }

    /**
     * Save customer address
     *
     * @return Mixed
     */
    public function saveCustomerAddress($data)
    {
        if(!$cart = $this->getCart())
            return false;

        $billingAddress = $data['billing'];
        $shippingAddress = $data['shipping'];
        $billingAddress['cart_id'] = $shippingAddress['cart_id'] = $cart->id;

        if($billingAddressModel = $cart->biling_address) {
            $this->cartAddress->update($billingAddress, $billingAddressModel->id);

            if($shippingAddress = $cart->shipping_address) {
                if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->update($billingAddress, $shippingAddress->id);
                } else {
                    $this->cartAddress->update($shippingAddress, $shippingAddress->id);
                }
            } else {
                if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                }
            }
        } else {
            $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'billing']));

            if(isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
            } else {
                $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
            }
        }

        return true;
    }

    /**
     * Save shipping method for cart
     *
     * @param string $shippingMethodCode
     * @return Mixed
     */
    public function saveShippingMethod($shippingMethodCode)
    {
        if(!$cart = $this->getCart())
            return false;

        foreach($cart->shipping_rates as $rate) {
            if($rate->method != $shippingMethodCode) {
                $rate->delete();
            }
        }

        return true;
    }
}