<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\WishlistResource;

class WishlistController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected WishlistRepository $wishlistRepository,
        protected ProductRepository $productRepository
    ) {
    }

    /**
     * Displays the listing resources if the customer having items in wishlist.
     */
    public function index(): JsonResource
    {
        if (! core()->getConfigData('general.content.shop.wishlist_option')) {
            abort(404);
        }

        $this->removeInactiveItems();

        $items = $this->wishlistRepository
            ->where([
                'channel_id'  => core()->getCurrentChannel()->id,
                'customer_id' => auth()->guard('customer')->user()->id,
            ])
            ->get();

        return WishlistResource::collection($items);
    }

    /**
     * Function to add item to the wishlist.
     */
    public function store(): JsonResource
    {
        $customer = auth()->guard('customer')->user();

        $productId = request()->input('product_id');
        
        $product = $this->productRepository->with('parent')->find($productId);
            
        if (! $product) {
            return new JsonResource([
                'message' => trans('customer::app.product-removed'),
            ]);
        }
    
        $data = [
            'channel_id'  => core()->getCurrentChannel()->id,
            'product_id'  => $productId,
            'customer_id' => $customer->id,
        ];
    
        if ($product->parent && $product->parent->type !== 'configurable') {
            $product = $product->parent;
            $data['product_id'] = $product->id;
        }
    
        $wishlist = $this->wishlistRepository->findOneWhere($data);
    
        if (! $wishlist) {
            $wishlist = $this->wishlistRepository->create($data);
    
            return new JsonResource ([
                'message' => trans('customer::app.wishlist.success'),
            ]);
        }
    
        $this->wishlistRepository->findOneWhere([
            'product_id' => $data['product_id'],
        ])->delete();
    
        return new JsonResource ([
            'message' => trans('customer::app.wishlist.removed'),
        ]);
    }

    /**
     * Function to move item from wishlist to cart.
     * 
     * @param  int  $id
     */
    public function moveToCart($id): JsonResource
    {
        try {
            /**
             * To Do (@shivendre):
             * 
             * Need to remove request()->all() dependency. Only pass array keys which
             * are actaully needed.
             */
            $cart = Cart::addProduct(request()->input('product_id'), request()->all());

            /**
             * To Do (@devansh-webkul): Need to check this and improve cart facade.
             */
            if (
                is_array($cart)
                && isset($cart['warning'])
            ) {
                return new JsonResource([
                    'message' => $cart['warning'],
                ]);
            }

            if ($cart) {
                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere([
                        'product_id'  => request()->input('product_id'),
                        'customer_id' => $customer->id,
                    ]);
                }

                return new JsonResource([
                    'data'     => WishlistResource::collection($this->wishlistRepository->get()),
                    'message'  => trans('shop::app.components.products.item-add-to-cart'),
                ]);
            }
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Function to remove item to the wishlist.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResource
    {
        $this->wishlistRepository->delete($id);

        return new JsonResource([
            'data'    => WishlistResource::collection($this->wishlistRepository->get()),
            'message' => trans('shop::app.customers.account.wishlist.removed'),
        ]);
    }

    /**
     * Removing inactive wishlist item.
     *
     * @return int
     */
    protected function removeInactiveItems()
    {
        $customer = auth()->guard('customer')->user();

        $customer->load(['wishlist_items.product']);

        $inactiveItemIds = $customer->wishlist_items
            ->filter(fn ($item) => ! $item->product->status)
            ->pluck('product_id')
            ->toArray();

        return $customer->wishlist_items()
            ->whereIn('product_id', $inactiveItemIds)
            ->delete();
    }
}
