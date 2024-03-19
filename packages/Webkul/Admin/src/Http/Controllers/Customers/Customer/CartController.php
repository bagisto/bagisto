<?php

namespace Webkul\Admin\Http\Controllers\Customers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\CartItemResource;
use Webkul\Checkout\Repositories\CartItemRepository;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CartItemRepository $cartItemRepository)
    {
    }

    /**
     * Returns the compare items of the customer.
     */
    public function items(int $id): JsonResource
    {
        $cartItems = $this->cartItemRepository
            ->with('product')
            ->leftJoin('cart', 'cart_items.cart_id', 'cart.id')
            ->whereNull('cart_items.parent_id')
            ->where('cart.customer_id', $id)
            ->where('cart.is_active', 1)
            ->get();

        $items = [];

        foreach ($cartItems as $item) {
            $items[] = (object) [
                'id'         => $item->id,
                'product_id' => $item->product_id,
                'type'       => $item->type,
                'sku'        => $item->sku,
                'name'       => $item->name,
                'price'      => $item->product->price,
                'quantity'   => $item->quantity,
                'additional' => $item->additional,
                'total'      => $item->total,
                'product'    => (object) [
                    'images' => $item->product->images,
                ],
            ];
        }

        return CartItemResource::collection($items);
    }
}
