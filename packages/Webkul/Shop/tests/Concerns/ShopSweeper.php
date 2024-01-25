<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Support\Facades\DB;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CartRule\Models\CartRuleCustomer;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Category\Models\Category as CategoryModel;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Models\SubscribersList;
use Webkul\Customer\Models\CompareItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Customer as CustomerModel;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Models\Wishlist;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Models\ProductCustomerGroupPrice;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

trait ShopSweeper
{
    /**
     * Clean everything.
     */
    public function cleanAll(): void
    {
        $this->cleanDatabase();

        $this->cleanElasticSearchIndices();
    }

    /**
     * Clean database.
     */
    public function cleanDatabase(): void
    {
        Cart::query()->delete();
        CartItem::query()->delete();
        CartPayment::query()->delete();
        CartRule::query()->delete();
        CartRuleCoupon::query()->delete();
        CartRuleCustomer::query()->delete();
        CatalogRule::query()->delete();
        CategoryModel::query()->whereNot('id', 1)->delete();
        CompareItem::query()->delete();
        Customer::query()->delete();
        CustomerAddress::query()->delete();
        CustomerModel::query()->delete();
        DB::table('product_inventory_indices')->truncate();
        DB::table('product_ordered_inventories')->truncate();
        Invoice::query()->delete();
        Order::query()->delete();
        OrderAddress::query()->delete();
        OrderItem::query()->delete();
        OrderPayment::query()->delete();
        Product::query()->delete();
        ProductCustomerGroupPrice::query()->delete();
        ProductInventory::query()->delete();
        ProductModel::query()->delete();
        ProductOrderedInventory::query()->delete();
        ProductReview::query()->delete();
        SubscribersList::query()->delete();
        TaxCategory::query()->delete();
        TaxMap::query()->delete();
        TaxRate::query()->delete();
        Wishlist::query()->delete();
        CartShippingRate::query()->delete();
    }

    /**
     * Clean elastic search indices.
     */
    public function cleanElasticSearchIndices(): void
    {
    }
}
