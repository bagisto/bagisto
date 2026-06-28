<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerProduct;
use Webkul\Product\Models\ProductFlat;

class StoreController extends Controller
{
    /**
     * Public storefront for a seller, reachable at /loja/{shop_url}.
     */
    public function show(string $shopUrl): View
    {
        /** @var Seller $seller */
        $seller = Seller::where('shop_url', $shopUrl)
            ->where('status', 'approved')
            ->firstOrFail();

        $productIds = SellerProduct::where('seller_id', $seller->id)
            ->where('status', 'approved')
            ->pluck('product_id');

        $products = ProductFlat::with('product.images')
            ->whereIn('product_id', $productIds)
            ->where('status', 1)
            ->where('locale', app()->getLocale())
            ->where('channel', core()->getCurrentChannelCode())
            ->orderByDesc('id')
            ->paginate(12);

        return view('marketplace::shop.store.index', compact('seller', 'products'));
    }
}
