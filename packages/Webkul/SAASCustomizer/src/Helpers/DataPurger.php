<?php

namespace Webkul\SAASCustomizer\Helpers;

use Carbon\Carbon;
use DB;

use Company;

use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Inventory\Repositories\InventorySourceRepository as Inventory;
use Webkul\Core\Repositories\LocaleRepository as Locale;
use Webkul\Core\Repositories\CurrencyRepository as Currency;
use Webkul\Core\Repositories\ChannelRepository as Channel;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Attribute\Repositories\AttributeGroupRepository as AttributeGroup;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;
use Webkul\CMS\Repositories\CMSRepository as CMS;
use Log;

/**
 * Class meant for preparing functional and sample data required for functioning of a new seller
 */
class DataPurger
{
    /**
     * Company Repository instance
     */
    protected $company;

    /**
     * CategoryRepository instance
     */
    protected $category;

    /**
     * InventoryRepository instance
     */
    protected $inventory;

    /**
     * LocaleRepository instance
     */
    protected $locale;

    /**
     * CurrencyRepository instance
     */
    protected $currency;

    /**
     * ChannelRepository instance
     */
    protected $channel;

    /**
     * AttributeRepository instance
     */
    protected $attribute;

    /**
     * AttributeFamilyRepository instance
     */
    protected $attributeFamily;

    /**
     * AttributeGroupRepository instance
     */
    protected $attributeGroup;

    /**
     * CustomerGroupRepository instance
     */
    protected $customerGroup;

    /**
     * CMSRepository instance
     */
    protected $cms;

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
        CMS $cms
    )
    {
        // $this->company = Company::getCurrent();
        $this->category = $category;
        $this->inventory = $inventory;
        $this->locale = $locale;
        $this->currency = $currency;
        $this->channel = $channel;
        $this->attribute = $attribute;
        $this->attributeFamily = $attributeFamily;
        $this->attributeGroup = $attributeGroup;
        $this->customerGroup = $customerGroup;
        $this->cms = $cms;
    }

    /**
     * Prepare data for the customer groups
     */
    public function prepareCustomerGroupData()
    {
        $this->company = Company::getCurrent();

        $data = [
            'code' => 'guest',
            'name' => 'Guest',
            'is_user_defined' => 0,
        ];

        $customerGroup0 = $this->customerGroup->create($data);

        // default customer group 1
        $data = [
            'id' => 1,
            'code' => 'general',
            'name' => 'General',
            'is_user_defined' => 0,
            'company_id' => $this->company->id
        ];

        $customerGroup1 = $this->customerGroup->create($data);

        // default customer group 2
        $data = [
            'id' => 2,
            'code' => 'wholesale',
            'name' => 'Wholesale',
            'is_user_defined' => 0,
            'company_id' => $this->company->id
        ];

        $customerGroup2 = $this->customerGroup->create($data);

        return ['default' => $customerGroup1, 'wholesale' => $customerGroup2];
    }

    /**
     * Prepares category data
     */
    public function prepareCategoryData()
    {
        $this->company = Company::getCurrent();

        $data = [
            'position' => '1',
            'image' => NULL,
            'status' => '1',
            'parent_id' => NULL,
            'name' => 'Root',
            'slug' => 'root',
            'description' => 'Root',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'locale' => 'all',
            'company_id' => $this->company->id
        ];

        $category = $this->category->create($data);

        return $category;
    }

    /**
     * Prepares data for a default inventory
     */
    public function prepareInventoryData()
    {
        $this->company = Company::getCurrent();

        $data = [
            'code' => 'default',
            'name' => 'Default',
            'contact_name' => 'Detroit Warehouse',
            'contact_email' => 'warehouse@example.com',
            'contact_number' => '9876543210',
            'status' => 1,
            'country' => 'US',
            'state' => 'MI',
            'street' => '12th Street',
            'city' => 'Detroit',
            'postcode' => '48127',
            'company_id' => $this->company->id
        ];

        return $this->inventory->create($data);
    }

    /**
     * Creates a default locale
     */
    public function prepareLocaleData()
    {
        $this->company = Company::getCurrent();

        $data = [
            'code' => 'en',
            'name' => 'English',
            'company_id' => $this->company->id
        ];

        return $this->locale->create($data);
    }

    /**
     * Prepares a default currency
     */
    public function prepareCurrencyData()
    {
        $this->company = Company::getCurrent();

        $data = [
            'code' => 'USD',
            'name' => 'US Dollar',
            'company_id' => $this->company->id
        ];

        return $this->currency->create($data);
    }

    /**
     * Prepares a default channel
     */
    public function prepareChannelData()
    {
        $this->company = Company::getCurrent();

        $inventorySource = $this->inventory->findOneWhere(['company_id' => $this->company->id]);
        $locale = $this->locale->findOneWhere(['company_id' => $this->company->id]);
        $currency = $this->currency->findOneWhere(['company_id' => $this->company->id]);
        $category = $this->category->findOneWhere(['company_id' => $this->company->id]);

        $data = [
            'code' => 'default',
            'name' => 'Default Channel',
            'description' => 'Default Channel',
            "inventory_sources" => [
                0 => $inventorySource->id
            ],
            "root_category_id" => $category->id,
            'hostname' => $this->company->domain,
            'locales' => [
                0 => $locale->id
            ],
            "default_locale_id" => $locale->id,

            'currencies' => [
                0 => $currency->id
            ],

            'base_currency_id' => $currency->id,
            'theme' => 'default',

            'home_page_content' => '<p>@include("shop::home.slider") @include("shop::home.featured-products") @include("shop::home.new-products")</p><div class="banner-container"><div class="left-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581f9494b8a1.png" /></div><div class="right-banner"><img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fb045cf02.png" /> <img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/1/201902045c581fc352d803.png" /></div></div>',

            'footer_content' => '<div class="list-container"><span class="list-heading">Quick Links</span><ul class="list-group"><li><a href="@php echo route("shop.cms.page", "about-us") @endphp">About Us</a></li><li><a href="@php echo route("shop.cms.page", "return-policy") @endphp">Return Policy</a></li><li><a href="@php echo route("shop.cms.page", "refund-policy") @endphp">Refund Policy</a></li><li><a href="@php echo route("shop.cms.page", "terms-conditions") @endphp">Terms and conditions</a></li><li><a href="@php echo route("shop.cms.page", "terms-of-use") @endphp">Terms of Use</a></li><li><a href="@php echo route("shop.cms.page", "contact-us") @endphp">Contact Us</a></li></ul></div><div class="list-container"><span class="list-heading">Connect With Us</span><ul class="list-group"><li><a href="#"><span class="icon icon-facebook"></span>Facebook </a></li><li><a href="#"><span class="icon icon-twitter"></span> Twitter </a></li><li><a href="#"><span class="icon icon-instagram"></span> Instagram </a></li><li><a href="#"> <span class="icon icon-google-plus"></span>Google+ </a></li><li><a href="#"> <span class="icon icon-linkedin"></span>LinkedIn </a></li></ul></div>',

            'company_id' => $this->company->id
        ];

        return $this->channel->create($data);

        // $newChannel->locales()->sync($data['locales']);

        // $newChannel->currencies()->sync($data['currencies']);

        // $newChannel->inventory_sources()->sync($data['inventory_sources']);

        // $this->uploadImages($data, $channel);

        // $this->uploadImages($data, $channel, 'favicon');
    }

    /**
     * Prepare Attribute Data
     */
    public function prepareAttributeData()
    {
        $this->company = Company::getCurrent();

        $sku = ['code' => 'sku','admin_name' => 'SKU','type' => 'text','validation' => NULL,'position' => '1','is_required' => '1','is_unique' => '1','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'SKU']];

        $this->attribute->create($sku);

        $name = ['code' => 'name', 'admin_name' => 'Name', 'type' => 'text', 'validation' => NULL, 'position' => '2', 'is_required' => '1', 'is_unique' => '0', 'value_per_locale' => '1', 'value_per_channel' => '1', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'company_id' => $this->company->id,'en' => ['name' => 'Name']];

        $this->attribute->create($name);

        $url_key = ['code' => 'url_key', 'admin_name' => 'URL Key', 'type' => 'text', 'validation' => NULL, 'position' => '3', 'is_required' => '1', 'is_unique' => '1', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'URL Key']];

        $this->attribute->create($url_key);

        $taxCategoryId = ['code' => 'tax_category_id', 'admin_name' => 'Tax Category', 'type' => 'select', 'validation' => NULL, 'position' => '4', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '1', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Tax Category']];

        $this->attribute->create($taxCategoryId);

        $new = ['code' => 'new', 'admin_name' => 'New', 'type' => 'boolean', 'validation' => NULL, 'position' => '5', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0','is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'New']];

        $this->attribute->create($new);

        $featured = ['id' => '6', 'code' => 'featured', 'admin_name' => 'Featured', 'type' => 'boolean', 'validation' => NULL, 'position' => '6', 'is_required' => '0', 'is_unique' => '0', 'value_per_locale' => '0', 'value_per_channel' => '0', 'is_filterable' => '0', 'is_configurable' => '0', 'is_user_defined' => '0', 'is_visible_on_front' => '0', 'company_id' => 1, 'en' => ['name' => 'Featured']];

        $this->attribute->create($featured);

        $visibleIndividually = ['code' => 'visible_individually','admin_name' => 'Visible Individually','type' => 'boolean','validation' => NULL,'position' => '7','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Visible Individually']];

        $this->attribute->create($visibleIndividually);

        $status = ['code' => 'status','admin_name' => 'Status','type' => 'boolean','validation' => NULL,'position' => '8','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Status']];

        $this->attribute->create($status);

        $shortDesc = ['code' => 'short_description','admin_name' => 'Short Description','type' => 'textarea','validation' => NULL,'position' => '9','is_required' => '1','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Short Description']];

        $this->attribute->create($shortDesc);

        $desc = ['code' => 'description','admin_name' => 'Description','type' => 'textarea','validation' => NULL,'position' => '10','is_required' => '1','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Description']];

        $this->attribute->create($desc);

        $price = ['code' => 'price','admin_name' => 'Price','type' => 'price','validation' => 'decimal','position' => '11','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Price']];

        $this->attribute->create($price);

        $cost = ['code' => 'cost','admin_name' => 'Cost','type' => 'price','validation' => 'decimal','position' => '12','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => 1, 'en' => ['name' => 'Cost']];

        $this->attribute->create($cost);

        $specialPrice = ['code' => 'special_price','admin_name' => 'Special Price','type' => 'price','validation' => 'decimal','position' => '13','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Special Price']];

        $this->attribute->create($specialPrice);

        $specialFrom = ['code' => 'special_price_from','admin_name' => 'Special Price From','type' => 'date','validation' => NULL,'position' => '14','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Special Price From']];

        $this->attribute->create($specialFrom);

        $specialTo = ['code' => 'special_price_to','admin_name' => 'Special Price To','type' => 'date','validation' => NULL,'position' => '15','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Special Price To']];

        $this->attribute->create($specialTo);

        $metaTitle = ['code' => 'meta_title','admin_name' => 'Meta Title','type' => 'textarea','validation' => NULL,'position' => '16','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Meta Title']];

        $this->attribute->create($metaTitle);

        $metaKeywords = ['code' => 'meta_keywords','admin_name' => 'Meta Keywords','type' => 'textarea','validation' => NULL,'position' => '17','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0', 'is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0','company_id' => $this->company->id, 'en' => ['name' => 'Meta Keywords']];

        $this->attribute->create($metaKeywords);

        $metaDesc = ['code' => 'meta_description','admin_name' => 'Meta Description','type' => 'textarea','validation' => NULL,'position' => '18','is_required' => '0','is_unique' => '0','value_per_locale' => '1','value_per_channel' => '1','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Meta Description']];

        $this->attribute->create($metaDesc);

        $width = ['code' => 'width','admin_name' => 'Width','type' => 'text','validation' => 'decimal','position' => '19','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Width']];

        $this->attribute->create($width);

        $height = ['code' => 'height','admin_name' => 'Height','type' => 'text','validation' => 'decimal','position' => '20','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Height']];

        $this->attribute->create($height);

        $depth = ['code' => 'depth','admin_name' => 'Depth','type' => 'text','validation' => 'decimal','position' => '21','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Depth']];

        $this->attribute->create($depth);

        $weight = ['code' => 'weight','admin_name' => 'Weight','type' => 'text','validation' => 'decimal','position' => '22','is_required' => '1','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '0','is_configurable' => '0','is_user_defined' => '0','is_visible_on_front' => '0','company_id' => $this->company->id, 'en' => ['name' => 'Weight']];

        $this->attribute->create($weight);

        $color = ['code' => 'color','admin_name' => 'Color','type' => 'select','validation' => NULL,'position' => '23','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '1','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Color'], 'options' => [
            'option_0' => ['admin_name' => 'Red', 'en' => ['label' => 'Red'], 'sort_order' => '1'],
            'option_1' => ['admin_name' => 'Green', 'en' => ['label' => 'Green'],'sort_order' => '2'],
            'option_2' => ['admin_name' => 'Yellow', 'en' => ['label' => 'Yellow'], 'sort_order' => '3'],
            'option_3' => ['admin_name' => 'Black', 'en' => ['label' => 'Black'], 'sort_order' => '4'],
            'option_4' => ['admin_name' => 'White', 'en' => ['label' => 'White'], 'sort_order' => '5']
        ]];

        $this->attribute->create($color);

        $size = ['code' => 'size','admin_name' => 'Size','type' => 'select','validation' => NULL,'position' => '24','is_required' => '0','is_unique' => '0','value_per_locale' => '0','value_per_channel' => '0','is_filterable' => '1','is_configurable' => '1','is_user_defined' => '1','is_visible_on_front' => '0', 'company_id' => $this->company->id, 'en' => ['name' => 'Size'], 'options' => [
            'option_0' => ['id' => '6','admin_name' => 'S', 'en' => ['label' => 'S'], 'sort_order' => '1'],
            'option_1' => ['id' => '7','admin_name' => 'M', 'en' => ['label' => 'M'], 'sort_order' => '2'],
            'option_2' => ['id' => '8','admin_name' => 'L', 'en' => ['label' => 'L'], 'sort_order' => '3'],
            'option_3' => ['id' => '9','admin_name' => 'XL', 'en' => ['label' => 'XL'], 'sort_order' => '4']
        ]];

        $this->attribute->create($size);

        return true;
    }

    /**
     * To prepare the attribute family
     */
    public function prepareAttributeFamilyData()
    {
        $this->company = Company::getCurrent();

        $data = ['code' => 'default', 'name' => 'Default', 'status' => '0', 'is_user_defined' => '1', 'company_id' => $this->company->id];

        $attributeFamily = $this->attributeFamily->create($data);

        return $attributeFamily;
    }

    /**
     * To prepare the attribute group mappings
     */
    public function prepareAttributeGroupData()
    {
        $this->company = Company::getCurrent();

        $attributeFamily = $this->attributeFamily->findOneWhere(['company_id' => $this->company->id]);
        $attributes = $this->attribute->all();

        $group1 = ['sku', 'name', 'url_key', 'tax_category_id', 'new', 'featured', 'visible_individually', 'status', 'color', 'size'];
        $group2 = ['short_description', 'description'];
        $group3 = ['meta_title', 'meta_keywords', 'meta_description'];
        $group4 = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to'];
        $group5 = ['width', 'height', 'depth', 'weight'];

        // creating group 1
        $this->attributeGroup->create(['name' => 'General', 'position' => '1', 'is_user_defined' => '0', 'attribute_family_id' => $attributeFamily->id, 'company_id' => $this->company->id]);

        $attributeGroup = $this->attributeGroup->findOneWhere(['name' => 'General']);

        $i = 1;
        foreach($group1 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        ['attribute_id' => $value->id, 'attribute_group_id' => $attributeGroup->id, 'position' => $i]
                    ]);
                }
            }
        }

        // creating group 2
        $g2 = $this->attributeGroup->create(['name' => 'Description', 'position' => '2', 'is_user_defined' => '0', 'attribute_family_id' => $attributeFamily->id, 'company_id' => $this->company->id]);

        $attributeGroup = $this->attributeGroup->findOneWhere(['name' => 'Description']);

        $i = 1;
        foreach($group2 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        ['attribute_id' => $value->id, 'attribute_group_id' => $attributeGroup->id, 'position' => $i]
                    ]);
                }
            }
        }

        // creating group 3
        $g3 = $this->attributeGroup->create(['name' => 'Meta Description', 'position' => '3', 'is_user_defined' => '0', 'attribute_family_id' => $attributeFamily->id, 'company_id' => $this->company->id]);

        $attributeGroup = $this->attributeGroup->findOneWhere(['name' => 'Meta Description']);

        $i = 1;
        foreach($group3 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        ['attribute_id' => $value->id, 'attribute_group_id' => $attributeGroup->id, 'position' => $i]
                    ]);
                }
            }
        }

        // creating group 4
        $g4 = $this->attributeGroup->create(['name' => 'Price', 'position' => '4', 'is_user_defined' => '0', 'attribute_family_id' => $attributeFamily->id, 'company_id' => $this->company->id]);

        $attributeGroup = $this->attributeGroup->findOneWhere(['name' => 'Price']);

        $i = 1;
        foreach($group4 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        ['attribute_id' => $value->id, 'attribute_group_id' => $attributeGroup->id, 'position' => $i]
                    ]);
                }
            }
        }

        // creating group 5
        $g5 = $this->attributeGroup->create(['name' => 'Shipping', 'position' => '5', 'is_user_defined' => '0', 'attribute_family_id' => $attributeFamily->id, 'company_id' => $this->company->id]);

        $attributeGroup = $this->attributeGroup->findOneWhere(['name' => 'Shipping']);

        $i = 1;
        foreach($group5 as $code) {
            $i++;

            foreach ($attributes as $value) {
                if($value->code == $code) {
                    DB::table('attribute_group_mappings')->insert([
                        ['attribute_id' => $value->id, 'attribute_group_id' => $attributeGroup->id, 'position' => $i]
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * To prepare the country state data for
     * admin and customers country & state fields
     * auto population
     */
    public function prepareCountryStateData()
    {
        $countries = json_decode(file_get_contents(base_path().'/packages/Webkul/Core/src/Data/countries.json'), true);

        DB::table('countries')->insert($countries);

        $states = json_decode(file_get_contents(base_path().'/packages/Webkul/Core/src/Data/states.json'), true);

        DB::table('country_states')->insert($states);

        return true;
    }

    /**
     * To prepare the cms pages data for the seller's shop
     */
    public function prepareCMSPagesData($channel, $locale)
    {
        $aboutus = [
            'url_key' => 'about-us',
            'html_content' => '<div class="static-container one-column">
                               <div class="mb-5">About us page content</div>
                               </div>',
            'page_title' => 'About Us',
            'meta_title' => 'about us',
            'meta_description' => '',
            'meta_keywords' => 'aboutus',
            'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">About us page content</div>\r\n</div>",
                        "meta_title": "about us",
                        "page_title": "About Us",
                        "meta_keywords": "aboutus ", "meta_description": ""}',
            'channel_id' => $channel->id,
            'locale_id' => $locale->id,
            'company_id' => $this->company->id
        ];

        $this->cms->create($aboutus);

        $returnpolicy = [
                'url_key' => 'return-policy',
                'html_content' => '<div class="static-container one-column">
                                <div class="mb-5">Return policy page content</div>
                                </div>',
                'page_title' => 'Return Policy',
                'meta_title' => 'return policy',
                'meta_description' => '',
                'meta_keywords' => 'return, policy',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Return policy page content</div>\r\n</div>",
                            "meta_title": "return policy",
                            "page_title": "Return Policy",
                            "meta_keywords": "return, policy ", "meta_description": ""}',
                'channel_id' => $channel->id,
                'locale_id' => $locale->id,
                'company_id' => $this->company->id
            ];

        $this->cms->create($returnpolicy);

        $refundpolicy = [
                'url_key' => 'refund-policy',
                'html_content' => '<div class="static-container one-column">
                                <div class="mb-5">Refund policy page content</div>
                                </div>',
                'page_title' => 'Refund Policy',
                'meta_title' => 'Refund policy',
                'meta_description' => '',
                'meta_keywords' => 'refund, policy',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Refund policy page content</div>\r\n</div>",
                            "meta_title": "Refund policy",
                            "page_title": "Refund Policy",
                            "meta_keywords": "refund,policy ", "meta_description": ""}',
                'channel_id' => $channel->id,
                'locale_id' => $locale->id,
                'company_id' => $this->company->id
            ];

        $this->cms->create($refundpolicy);

        $termsconditions = [
                'url_key' => 'terms-conditions',
                'html_content' => '<div class="static-container one-column">
                                    <div class="mb-5">Terms & conditions page content</div>
                                    </div>',
                'page_title' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => '',
                'meta_keywords' => 'term, conditions',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Terms & conditions page content</div>\r\n</div>",
                            "meta_title": "Terms & Conditions",
                            "page_title": "Terms & Conditions",
                            "meta_keywords": "terms, conditions ", "meta_description": ""}',
                'channel_id' => $channel->id,
                'locale_id' => $locale->id,
                'company_id' => $this->company->id
            ];

        $this->cms->create($termsconditions);

        $termsofuse = [
            'url_key' => 'terms-of-use',
            'html_content' => '<div class="static-container one-column">
                            <div class="mb-5">Terms of use page content</div>
                            </div>',
            'page_title' => 'Terms of use',
            'meta_title' => 'Terms of use',
            'meta_description' => '',
            'meta_keywords' => 'term, use',
            'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Terms of use page content</div>\r\n</div>",
                        "meta_title": "Terms of use",
                        "page_title": "Terms of use",
                        "meta_keywords": "terms, use ", "meta_description": ""}',
            'channel_id' => $channel->id,
            'locale_id' => $locale->id,
            'company_id' => $this->company->id
        ];

        $this->cms->create($termsofuse);

        $contactus = [
                'url_key' => 'contact-us',
                'html_content' => '<div class="static-container one-column">
                                <div class="mb-5">Contact us page content</div>
                                </div>',
                'page_title' => 'Contact Us',
                'meta_title' => 'Contact Us',
                'meta_description' => '',
                'meta_keywords' => 'contact, us',
                'content' => '{"html": "<div class=\"static-container one-column\">\r\n<div class=\"mb-5\">Contact us page content</div>\r\n</div>",
                            "meta_title": "Contact Us",
                            "page_title": "Contact Us",
                            "meta_keywords": "contact, us ", "meta_description": ""}',
                'channel_id' => $channel->id,
                'locale_id' => $locale->id,
                'company_id' => $this->company->id
            ];

        $this->cms->create($contactus);
    }

    /**
     * It will store a check in the companies
     * that all the necessary data had been
     * inserted successfully or not
     *
     * @param boolean seedCompleted
     */
    public function setInstallationCompleteParam()
    {
        $this->seedCompleted = true;

        $info = [
            'company_created' => true,
            'seeded' => true
        ];

        $info = json_encode($info);

        $this->company->update([
            'more_info' => $info
        ]);

        return true;
    }
}