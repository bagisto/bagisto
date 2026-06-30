<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;

class WebMcpController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected WishlistRepository $wishlistRepository
    ) {}

    /**
     * Resolve a product by name/url-key and open its detail page.
     */
    public function product(): RedirectResponse
    {
        $query = trim((string) request('query'));

        if ($query !== '' && $product = $this->resolveProduct($query)) {
            return redirect()->route('shop.product_or_category.index', $product->url_key);
        }

        return redirect()->route('shop.search.index', ['query' => $query]);
    }

    /**
     * Resolve a product by name/url-key and add it to the customer's wishlist.
     */
    public function addToWishlist(): RedirectResponse
    {
        if (! auth()->guard('customer')->check()) {
            return redirect()->route('shop.customer.session.index');
        }

        $query = trim((string) request('query'));

        $product = $query !== '' ? $this->resolveProduct($query) : null;

        if (! $product) {
            session()->flash('warning', trans('shop::app.customers.account.wishlist.product-removed'));

            return redirect()->route('shop.search.index', ['query' => $query]);
        }

        $data = [
            'channel_id' => core()->getCurrentChannel()->id,
            'product_id' => $product->id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ];

        if (! $this->wishlistRepository->findOneWhere($data)) {
            Event::dispatch('customer.wishlist.create.before', $product->id);

            $wishlist = $this->wishlistRepository->create($data);

            Event::dispatch('customer.wishlist.create.after', $wishlist);

            session()->flash('success', trans('shop::app.customers.account.wishlist.success'));
        }

        return redirect()->route('shop.customers.account.wishlist.index');
    }

    /**
     * Best-effort resolution of a product from a name or url-key.
     */
    protected function resolveProduct(string $query)
    {
        $product = $this->productRepository->findBySlug($query)
            ?? $this->productRepository->findBySlug(Str::slug($query));

        if ($product) {
            return $product;
        }

        return $this->productRepository->getAll([
            'query' => $query,
            'status' => 1,
            'visible_individually' => 1,
            'limit' => 1,
        ])->first();
    }
}
