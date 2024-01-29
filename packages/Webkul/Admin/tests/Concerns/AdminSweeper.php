<?php

namespace Webkul\Admin\Tests\Concerns;

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily as AttributeFamilyModel;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Category\Models\Category;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\CMS\Models\Page;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Core\Models\Locale;
use Webkul\Core\Models\SubscribersList;
use Webkul\Core\Models\Visit;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Customer\Models\Wishlist;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;
use Webkul\Marketing\Models\SearchSynonym;
use Webkul\Marketing\Models\SearchTerm;
use Webkul\Marketing\Models\Template;
use Webkul\Marketing\Models\URLRewrite;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Models\ShipmentItem;
use Webkul\Sitemap\Models\Sitemap;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;
use Webkul\Theme\Models\ThemeCustomization;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

trait AdminSweeper
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
        Admin::query()->whereNot('id', 1)->delete();
        Attribute::query()->whereNotBetween('id', [1, 28])->delete();
        AttributeFamilyModel::query()->whereNot('id', 1)->delete();
        Campaign::query()->delete();
        Cart::query()->delete();
        CartRule::query()->delete();
        CatalogRule::query()->delete();
        Category::query()->whereNot('id', 1)->delete();
        Channel::query()->whereNot('id', 1)->delete();
        Currency::query()->whereNot('id', 1)->delete();
        CurrencyExchangeRate::query()->delete();
        Customer::query()->delete();
        CustomerAddress::query()->delete();
        CustomerGroup::query()->whereNotBetween('id', [1, 3])->delete();
        Event::query()->whereNot('id', 1)->delete();
        InventorySource::query()->whereNot('id', 1)->delete();
        Invoice::query()->delete();
        InvoiceItem::query()->delete();
        Locale::query()->whereNot('id', 1)->delete();
        Order::query()->delete();
        OrderAddress::query()->delete();
        OrderItem::query()->delete();
        CartItem::query()->delete();
        OrderPayment::query()->delete();
        Page::query()->whereNotBetween('id', [1, 11])->delete();
        Product::query()->delete();
        ProductReview::query()->delete();
        Refund::query()->delete();
        Role::query()->whereNot('id', 1)->delete();
        SearchSynonym::query()->delete();
        SearchTerm::query()->delete();
        Shipment::query()->delete();
        ShipmentItem::query()->delete();
        Sitemap::query()->delete();
        SubscribersList::query()->delete();
        TaxCategory::query()->delete();
        TaxRate::query()->delete();
        Template::query()->delete();
        ThemeCustomization::query()->whereNotBetween('id', [1, 12])->delete();
        URLRewrite::query()->delete();
        Visit::query()->delete();
        Wishlist::query()->delete();
    }

    /**
     * Clean elastic search indices.
     */
    public function cleanElasticSearchIndices(): void
    {
    }
}
