<?php

namespace Webkul\SAASCustomizer\Helpers;

use Company;
use DB;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as Inventory;
use Webkul\Core\Repositories\LocaleRepository as Locale;
use Webkul\Core\Repositories\CurrencyRepository as Currency;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Attribute\Repositories\AttributeGroupRepository as AttributeGroup;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Class meant for extracting essential information about the seller
 */
class StatsPurger
{
    protected $category;
    protected $inventory;
    protected $locale;
    protected $currency;
    protected $channel;
    protected $attribute;
    protected $attributeFamily;
    protected $attributeGroup;
    protected $customerGroup;
    protected $product;
    protected $seedCompleted = true;

    public function __construct(
        Category $category,
        Inventory $inventory,
        Locale $locale,
        Currency $currency,
        Channel $channel,
        Attribute $attribute,
        AttributeFamily $attributeFamily,
        AttributeGroup $attributeGroup,
        CustomerGroup $customerGroup,
        Product $product
    )
    {
        $this->category = $category;
        $this->inventory = $inventory;
        $this->locale = $locale;
        $this->currency = $currency;
        $this->channel = $channel;
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->attributeGroup = $attributeGroup;
        $this->customerGroup = $customerGroup;
        $this->product = $product;
    }

    public function getAggregates($companyId)
    {
        $products = DB::table('products')->where('company_id', '=', $companyId)->count();
        $attributes = DB::table('attributes')->where('company_id', '=', $companyId)->count();
        $customers = DB::table('customers')->where('company_id', '=', $companyId)->count();
        $customerGroups = DB::table('customer_groups')->where('company_id', '=', $companyId)->count();
        $categories = DB::table('categories')->where('company_id', '=', $companyId)->count();

        return [
            'products' => $products,
            'attributes' => $attributes,
            'customers' => $customers,
            'customer-groups' => $customerGroups,
            'categories' => $categories
        ];
    }
}