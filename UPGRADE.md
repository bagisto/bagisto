# UPGRADE Guide

- [Upgrading To v2.2.0 From v2.1.0](#upgrade-2.2.0)

## High Impact Changes

- [Updating Dependencies](#updating-dependencies)
- [The `Webkul\Checkout\Cart` class](#the-cart-class)
- [The `Webkul\Product\Type\Configurable` class](#the-configurable-type-class)
- [Shop API Response Updates](#the-shop-api-response-updates)
- [Admin and Shop Menu Updates](#the-admin-shop-menu-updates)
- [Admin ACL Updates](#the-admin-acl-updates)

## Medium Impact Changes

- [Admin Customized Datagrid Parameters Updated](admin-customized-datagrid-parameters-updated)
- [Admin Event Updates](#admin-event-updates)
- [The System Configuration Updates](#the-system-config-update)
- [The `Webkul\Checkout\Models\Cart` model](#the-cart-model)
- [The Checkout Tables Schema Updates](#the-checkout-tables-schema-updates)
- [The `Webkul\DataGrid\DataGrid` class](#the-datagrid-class)
- [The `Webkul/DataGrid/src/Column` class](#the-column-class)
- [The `Webkul\Product\Repositories\ElasticSearchRepository` Repository](#the-elastic-search-repository)
- [The `Webkul\Product\Repositories\ProductRepository` Repository](#the-product-repository)
- [The product Elastic Search indexing](#the-elastic-indexing)
- [The Sales Tables Schema Updates](#the-sales-tables-schema-updates)
- [The `Webkul\Sales\Repositories\OrderItemRepository` Repository](#the-order-item-repository)
- [The `Webkul\Tax\Helpers\Tax` Class Moved](#moved-tax-helper-class)
- [Shop Event Updates](#shop-event-updates)
- [Shop Customized Datagrid Parameters Updated](#shop-customized-datagrid-parameters-updated)
- [Shop Class HTML Attribute updated](#shop-class-attribute-updated)

## Low Impact Changes

- [Renamed Admin API Route Names](#renamed-admin-api-routes-names)
- [Renamed Admin Controller Method Names](#renamed-admin-controller-method-names)
- [Removed Cart Traits](#removed-cart-traits)
- [The Product Types Classes Updates](#the-product-type-class)
- [Renamed `star-rating.blade.php`](#renamed-star-rating-blade)
- [Moved `coupon.blade.php`](#moved-coupon-blade)
- [Renamed Shop API Route Names](#renamed-shop-api-routes-names)
- [Renamed Shop Controller Method Names](#renamed-shop-controller-method-names)
- [Renamed Admin View Render Event Names](#renamed-admin-view-render-event-names)
- [Repositories Update Method Signature Updated](#repository-update-method-signature-updated)
- [Optimize The Configuration Section](#optimize-the-configuration-section)

## Upgrading To v2.2.0 From v2.1.0

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Updating Dependencies

**Impact Probability: High**

#### PHP 8.2.0 Required

Laravel now requires PHP 8.1.0 or greater.

#### curl 7.34.0 Required

Laravel's HTTP client now requires curl 7.34.0 or greater.

#### Composer Dependencies

There is no dependency needed to be updated at for this upgrade.

<div class="content-list" markdown="1">
</div>



<a name="Admin"></a>
### Admin

<a name="the-system-config-update"></a>
#### The System Configuration Updates

**Impact Probability: Medium**

1: The tax configuration has been relocated to the sales configuration, and the respective path for retrieving configuration values has been updated accordingly.

```diff
- core()->getConfigData('taxes.catalogue.pricing.tax_inclusive')
+ core()->getConfigData('sales.taxes.calculation.product_prices')

+ core()->getConfigData('sales.taxes.calculation.shipping_prices')

- core()->getConfigData('taxes.catalogue.default_location_calculation.country')
+ core()->getConfigData('sales.taxes.default_destination_calculation.country')

- core()->getConfigData('taxes.catalogue.default_location_calculation.state')
+ core()->getConfigData('sales.taxes.default_destination_calculation.state')

- core()->getConfigData('taxes.catalogue.default_location_calculation.postcode')
+ core()->getConfigData('sales.taxes.default_destination_calculation.postcode')
```

2: The `repository` option has been replaced with `options`. Now, you can use `options` as shown below to populate select field options from the database.

```diff
'key'    => 'sales.taxes.categories',
'name'   => 'admin::app.configuration.index.sales.taxes.categories.title',
'info'   => 'admin::app.configuration.index.sales.taxes.categories.title-info',
'sort'   => 1,
'fields' => [
    [
        'name'       => 'shipping',
        'title'      => 'admin::app.configuration.index.sales.taxes.categories.shipping',
        'type'       => 'select',
        'default'    => 0,
-       'repository' => '\Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
+       'options'    => '\Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
}
```

3. The Inventory Stock Options configuration has been relocated to the Order Settings configuration, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('catalog.inventory.stock_options.back_orders')
+ core()->getConfigData('sales.order_settings.stock_options.back_orders')
```

4. The product storefront search mode configuration `core()->getConfigData('catalog.products.storefront.search_mode')` has been replaced with the following configurations, and the corresponding paths for retrieving configuration values have been updated:


```diff
- core()->getConfigData('catalog.products.storefront.search_mode')

+ core()->getConfigData('catalog.products.search.engine')
+ core()->getConfigData('catalog.products.search.admin_mode')
+ core()->getConfigData('catalog.products.search.storefront_mode')
```

`core()->getConfigData('catalog.products.search.engine')` represents the search engine for products. If "Elastic Search" is selected, elastic indexing will be enabled. The other two configurations (`catalog.products.search.admin_mode` and `catalog.products.search.storefront_mode`) will function relative to this configuration.

5: The `locale_based` setting in the sales shipping origin configuration has been updated to support multiple locale values.

```diff
[
    'key'    => 'sales.shipping.origin',
    'name'   => 'admin::app.configuration.index.sales.shipping.origin.title',
    'info'   => 'admin::app.configuration.index.sales.shipping.origin.title-info',
    'sort'   => 0,
    'fields' => [
        [
            'name'          => 'city',
            'title'         => 'admin::app.configuration.index.sales.shipping.origin.city',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
-           'locale_based'  => false,
+           'locale_based'  => true,
        ], [
            'name'          => 'address',
            'title'         => 'admin::app.configuration.index.sales.shipping.origin.street-address',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
-           'locale_based'  => false,
+           'locale_based'  => true,
        ], [
            'name'          => 'zipcode',
            'title'         => 'admin::app.configuration.index.sales.shipping.origin.zip',
            'type'          => 'text',
            'validation'    => 'required',
            'channel_based' => true,
-           'locale_based'  => false,
+           'locale_based'  => true,
        ], [
            'name'          => 'store_name',
            'title'         => 'admin::app.configuration.index.sales.shipping.origin.store-name',
            'type'          => 'text',
            'channel_based' => true,
-           'locale_based'  => false,
+           'locale_based'  => true,
        ], [
            'name'          => 'bank_details',
            'title'         => 'admin::app.configuration.index.sales.shipping.origin.bank-details',
            'type'          => 'textarea',
            'channel_based' => true,
-           'locale_based'  => false,
+           'locale_based'  => true,
        ],
    ],
]
```

If you are migrating your existing store to this version, please save the configuration values again, as previously saved values will no longer work.

6. The Allow Guest Checkout configuration has been relocated to the Sales configuration under Checkout, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('catalog.products.guest_checkout.allow_guest_checkout')
+ core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')
```

7. The Wishlist Option configuration has been relocated to the Customer configuration under Setting, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('general.content.shop.wishlist_option')
+ core()->getConfigData('customer.settings.wishlist.wishlist_option')
```

8. The `locale_based` and `channel_based` settings in the customer products settings configuration have been updated to no longer be locale- or channel-based.

```diff
[
    'key'    => 'catalog.products.settings',
    'name'   => 'admin::app.configuration.index.general.content.settings.title',
    'info'   => 'admin::app.configuration.index.general.content.settings.title-info',
    'sort'   => 1,
    'fields' => [
        [
            'name'          => 'compare_option',
            'title'         => 'admin::app.configuration.index.general.content.settings.compare-options',
            'type'          => 'boolean',
            'default'       => 1,
-           'locale_based'  => true,
-           'channel_based' => true,
        ], [
            'name'          => 'image_search',
            'title'         => 'admin::app.configuration.index.general.content.settings.image-search-option',
            'type'          => 'boolean',
            'default'       => 1,
-           'locale_based'  => true,
-           'channel_based' => true,
        ],
    ],
]
```

9. The `locale_based` and `channel_based` settings in the customer settings wishlist configuration have been updated to no longer be locale- or channel-based.

```diff
[
    'key'    => 'customer.settings.wishlist',
    'name'   => 'Wishlist',
    'info'   => 'Enable or disable the wishlist option.',
    'sort'   => 2,
    'fields' => [
        [
            'name'    => 'wishlist_option',
            'title'   => 'Allow Wishlist option',
            'type'    => 'boolean',
            'default' => 1,
-           'locale_based'  => true,
-           'channel_based' => true,
        ],
    ],
]
```

10. The Back Order configuration has been relocated to the Catalog Inventory under Stock Options, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('sales.order_settings.stock_options.back_orders')
+ core()->getConfigData('catalog.inventory.stock_options.back_orders')
```

11. The `channel_based` settings in the customer settings wishlist configuration have been updated to no longer be locale- or channel-based.

```diff
[
    'key'    => 'catalog.inventory.stock_options',
    'name'   => 'Product Stock Options',
    'info'   => 'Product Stock Options',
    'sort'   => 1,
    'fields' => [
        [
            'name'          => 'back_orders',
            'title'         => 'Allow Back Orders',
            'type'          => 'boolean',
            'default'       => false,
-           'channel_based' => true,
        ],
    ],
]
```

10. The Invoice Slip Design Logo configuration has been relocated to the Sales Invoice Setting under PDF Print Outs, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('sales.invoice_settings.invoice_slip_design.logo')
+ core()->getConfigData('sales.invoice_settings.pdf_print_outs.logo')
```


<a name="renamed-admin-api-routes-names"></a>
#### Renamed Admin API Route Names

**Impact Probability: Low**

1. In the `packages/Webkul/Admin/src/Routes/sales-routes.php` route file, route names and controller methods have been renamed to provide clearer and more meaningful representations.

```diff
- Route::post('update-qty/{order_id}', 'updateQty')->name('admin.sales.refunds.update_qty');
+ Route::post('update-totals/{order_id}', 'updateTotals')->name('admin.sales.refunds.update_totals');
```

<a name="admin-event-updates"></a>
#### Admin Event Updates

**Impact Probability: High**

#### Admin Event Inside Head Updated

1. The event that was previously added in Admin has now been updated in the new format. You can now directly add your own custom elements inside the <head> tag.

```diff
+ {!! view_render_event('bagisto.admin.layout.head.before') !!}

- {!! view_render_event('bagisto.admin.layout.head') !!}
+ {!! view_render_event('bagisto.admin.layout.head.after') !!}
```

<a name="renamed-admin-view-render-event-names"></a>
#### Renamed Admin View Render Event Names

**Impact Probability: Low**

1. The View render event names have been updated for consistency in the `packages/Webkul/Admin/src/Resources/views/dashboard/index.blade.php` blade file.

```diff
- {!! view_render_event('bagisto.admin.dashboard.overall_detailes.before') !!}
+ {!! view_render_event('bagisto.admin.dashboard.overall_details.before') !!}

- {!! view_render_event('bagisto.admin.dashboard.overall_detailes.after') !!}
+ {!! view_render_event('bagisto.admin.dashboard.overall_details.after') !!}

- {!! view_render_event('bagisto.admin.dashboard.todays_detailes.before') !!}
+ {!! view_render_event('bagisto.admin.dashboard.todays_details.before') !!}

- {!! view_render_event('bagisto.admin.dashboard.todays_detailes.after') !!}
+ {!! view_render_event('bagisto.admin.dashboard.todays_details.after') !!}
```

<a name="repository-update-method-signature-updated"></a>

#### Repository Update Method Signature Updated

1. We have updated the signature of the `update` method in the `Repositories` class to streamline its functionality and improve code clarity. The method previously accepted three arguments, but the third argument, `$attribute`, is no longer necessary. The updated method signature is as follows:

```diff
- public function update(array $data, $id, $attribute = 'id')
+ public function update(array $data, $id)
```

2. We have updated the signature of the `update` method in the `ProductRepository` to improve its functionality and provide greater flexibility in specifying which attributes should be updated. The method previously accepted three arguments, but the third argument, `$attribute`, has been modified to `$attributes`, which now accepts an array of attributes to be updated. The updated method signature is as follows:

```diff
- public function update(array $data, $id, $attribute = 'id')
+ public function update(array $data, $id, $attributes = [])

```

<a name="optimize-the-configuration-section"></a>

#### Optimize The Configuration Section

We are removing the `packages/Webkul/Core/src/Tree.php` file and also removing the `CoreConfigField` trait from `CoreConfigRepository.php` file. Its important methods have been moved into `packages/Webkul/Core/src/SystemConfig/ItemField.php` because the `ItemField.php` file is responsible for each field of configuration items.

```diff
-<?php
-
-namespace Webkul\Core;
-
-use Illuminate\Support\Facades\Request;
-
-class Tree
-{
-    /**
-     * Contains tree item
-     *
-     * @var array
-     */
-    public $items = [];
-
-    /**
-     * Contains acl roles
-     *
-     * @var array
-     */
-    public $roles = [];
-
-    /**
-     * Contains current item route
-     *
-     * @var string
-     */
-    public $current;
-
-    /**
-     * Contains current item key
-     *
-     * @var string
-     */
-    public $currentKey;
-
-    /**
-     * Create a new instance.
-     *
-     * @return void
-     */
-    public function __construct()
-    {
-        $this->current = Request::url();
-    }
-
-    /**
-     * Shortcut method for create a Config with a callback.
-     * This will allow you to do things like fire an event on creation.
-     *
-     * @param  callable  $callback  Callback to use after the Config creation
-     * @return object
-     */
-    public static function create($callback = null)
-    {
-        $tree = new Tree();
-
-        if ($callback) {
-            $callback($tree);
-        }
-
-        return $tree;
-    }
-
-    /**
-     * Add a Config item to the item stack
-     *
-     * @param  string  $item
-     * @return void
-     */
-    public function add($item, $type = '')
-    {
-        $item['children'] = [];
-
-        if ($type == 'menu') {
-            $item['url'] = route($item['route'], $item['params'] ?? []);
-
-            if (strpos($this->current, $item['url']) !== false) {
-                $this->currentKey = $item['key'];
-            }
-        } elseif ($type == 'acl') {
-            $item['name'] = trans($item['name']);
-
-            $this->roles[$item['route']] = $item['key'];
-        }
-
-        $children = str_replace('.', '.children.', $item['key']);
-
-        core()->array_set($this->items, $children, $item);
-    }
-}
```

```diff
-<?php
-
-namespace Webkul\Core\Traits;
-
-use Illuminate\Support\Str;
-
-trait CoreConfigField
-{
-    /**
-     * Laravel to Vee Validation mappings.
-     *
-     * @var array
-     */
-    protected $veeValidateMappings = [
-        'min'=> 'min_value',
-    ];
-
-    /**
-     * Get name field for forms in configuration page.
-     *
-     * @param  string  $key
-     * @return string
-     */
-    public function getNameField($key)
-    {
-        $nameField = '';
-
-        foreach (explode('.', $key) as $key => $field) {
-            $nameField .= $key === 0 ? $field : '['.$field.']';
-        }
-
-        return $nameField;
-    }
-
-    /**
-     * Get validations for forms in configuration page.
-     *
-     * @param  array  $field
-     * @return string
-     */
-    public function getValidations($field)
-    {
-        $field['validation'] = $field['validation'] ?? '';
-
-        foreach ($this->veeValidateMappings as $laravelRule => $veeValidateRule) {
-            $field['validation'] = str_replace($laravelRule, $veeValidateRule, $field['validation']);
-        }
-
-        return $field['validation'];
-    }
-
-    /**
-     * Get channel/locale indicator for form fields. So, that form fields can be detected,
-     * whether it is channel based or locale based or both.
-     *
-     * @param  array  $field
-     * @param  string  $channel
-     * @param  string  $locale
-     * @return string
-     */
-    public function getChannelLocaleInfo($field, $channel, $locale)
-    {
-        $info = [];
-
-        if (! empty($field['channel_based'])) {
-            $info[] = $channel;
-        }
-
-        if (! empty($field['locale_based'])) {
-            $info[] = $locale;
-        }
-
-        return ! empty($info) ? '['.implode(' - ', $info).']' : '';
-    }
-
-    /**
-     * Returns the select options for the field.
-     */
-    public function getOptions(array|string $options): array
-    {
-        if (is_array($options)) {
-            return $options;
-        }
-
-        [$class, $method] = Str::parseCallback($options);
-
-        return app($class)->$method();
-    }
-}

```

The method `getChannelLocaleInfo`, which was in the `CoreConfigField` trait, has been completely removed as it is no longer needed. The `getNameField`, `getValidations`, and `getOptions` methods, along with their class properties, have been moved to the `packages/Webkul/Core/src/SystemConfig/ItemField.php` file, which is handled by the `ItemField` class.

```diff
-    /**
-     * Get channel/locale indicator for form fields. So, that form fields can be detected,
-     * whether it is channel based or locale based or both.
-     *
-     * @param  array  $field
-     * @param  string  $channel
-     * @param  string  $locale
-     * @return string
-     */
-    public function getChannelLocaleInfo($field, $channel, $locale)
-    {
-        $info = [];
-
-        if (! empty($field['channel_based'])) {
-            $info[] = $channel;
-        }
-
-        if (! empty($field['locale_based'])) {
-            $info[] = $locale;
-        }
-
-        return ! empty($info) ? '['.implode(' - ', $info).']' : '';
-    }
```

To achieve this, we are using the code provided below.

```diff
+ <span
+     v-if="field['channel_based'] && channelCount"
+     class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
+     v-text="JSON.parse(currentChannel).name"
+ >
+ </span>
+ 
+ <span
+     v-if="field['locale_based']"
+     class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
+     v-text="JSON.parse(currentLocale).name"
+ >
+ </span>
```

In `packages/Webkul/Admin/src/Resources/views/configuration/index.blade.php`, we have completely changed the way to get/fetch the configuration items. We are now using `packages/Webkul/Core/src/SystemConfig/Item.php`, `packages/Webkul/Core/src/SystemConfig/ItemField.php`, and `packages/Webkul/Core/src/SystemConfig.php`, which are responsible for handling the configuration items and their fields.

The changes in `index.blade.php` are shown below:

```diff
<div class="grid gap-y-8">
-    @foreach ($config->items as $itemKey => $item)
+    @foreach (system_config()->getItems() as $item)
        <div>
            <div class="grid gap-1">
                <!-- Title of the Main Card -->
                <p class="font-semibold text-gray-600 dark:text-gray-300">
-                   @lang($item['name'] ?? '')
+                   {{ $item->getName() }}
                </p>

                <!-- Info of the Main Card -->
                <p class="text-gray-600 dark:text-gray-300">
-                   @lang($item['info'] ?? '')
+                   {{ $item->getInfo() }}
                </p>
            </div>

            <div class="box-shadow max-1580:grid-cols-3 mt-2 grid grid-cols-4 flex-wrap justify-between gap-12 rounded bg-white p-4 dark:bg-gray-900 max-xl:grid-cols-2 max-sm:grid-cols-1">
                <!-- Menus cards -->
-               @foreach ($item['children'] as $childKey =>  $child)
+               @foreach ($item->getChildren() as $key => $child)
                    <a 
                        class="flex max-w-[360px] items-center gap-2 rounded-lg p-2 transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
-                        href="{{ route('admin.configuration.index', ($itemKey . '/' . $childKey)) }}"
+                        href="{{ route('admin.configuration.index', ($item->getKey() . '/' . $key)) }}"
                    >
-                       @if (isset($child['icon']))
+                       @if ($icon = $child->getIcon())
                            <img
                                class="h-[60px] w-[60px] dark:mix-blend-exclusion dark:invert"
-                               src="{{ bagisto_asset('images/' . $child['icon'] ?? '') }}"
+                               src="{{ bagisto_asset('images/' . $icon) }}"
                            >
                        @endif

                        <div class="grid">
                            <p class="mb-1.5 text-base font-semibold text-gray-800 dark:text-white">
-                               @lang($child['name'])
+                               {{ $child->getName() }}
                            </p>

                            <p class="text-xs text-gray-600 dark:text-gray-300">
-                               @lang($child['info'] ?? '')
+                               {{ $child->getInfo() }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
```

In `packages/Webkul/Admin/src/Resources/views/configuration/edit.blade.php`, we were previously using iteration and conditions based on routes. Now, we are using the `facade` helper method named `system_config()` to get the active configuration items and their fields.

```diff
@php
    $channels = core()->getAllChannels();

    $currentChannel = core()->getRequestedChannel();

    $currentLocale = core()->getRequestedLocale();

+   $activeConfiguration = system_config()->getActiveConfigurationItem();
@endphp

<x-admin::layouts>
    <x-slot:title>
-        @if ($items = Arr::get($config->items, request()->route('slug') . '.children'))
-            @foreach ($items as $key => $item)
-                @if ( $key == request()->route('slug2'))
-                    {{ $title = trans($item['name']) }}
-                @endif
-            @endforeach
-        @endif

+       {{ $name = $activeConfiguration->getName() }}
    </x-slot>

    <p class="text-xl font-bold text-gray-800 dark:text-white">
-       {{ $title }}
+       {{ $name }}
    </p>
//
</x-admin::layouts>
```

```diff
-@if ($groups)
    <div class="mt-6 grid grid-cols-[1fr_2fr] gap-10 max-xl:flex-wrap">
-       @foreach ($groups as $key => $item)
+       @foreach ($activeConfiguration->getChildren() as $child)
            <div class="grid content-start gap-2.5">
                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
-                   @lang($item['name'])
+                   {{ $child->getName() }}
                </p>

                <p class="leading-[140%] text-gray-600 dark:text-gray-300">
-                   @lang($item['info'] ?? '')
+                   {!! $child->getInfo() !!}
                </p>
            </div>

            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
-               @foreach ($item['fields'] as $field)
+               @foreach ($child->getFields() as $field)
                    @if (
-                       $field['type'] == 'blade'
+                       $field->getType() == 'blade'
-                       && view()->exists($field['path'])
+                       && view()->exists($path = $field->getPath())
                    )
-                       {!! view($field['path'], compact('field'))->render() !!}
+                       {!! view($path, compact('field', 'item'))->render() !!}
                    @else 
                        @include ('admin::configuration.field-type')
                    @endif

-                   @php ($hint = $field['title'] . '-hint')
-
-                   @if ($hint !== __($hint))
-                       <p class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300">
-                           @lang($hint)
-                       </p>
-                   @endif
                @endforeach
            </div>
        @endforeach
    </div>
-@endif
```

The changes in field-type.blade.php are shown below:

```diff
-@inject('coreConfigRepository', 'Webkul\Core\Repositories\CoreConfigRepository')

@php
-    $nameKey = $item['key'] . '.' . $field['name'];
-    $name = $coreConfigRepository->getNameField($nameKey);
-    $validations = $coreConfigRepository->getValidations($field);
-    $isRequired = Str::contains($validations, 'required') ? 'required' : '';
-    $channelLocaleInfo = $coreConfigRepository->getChannelLocaleInfo($field, $currentChannel->code, $currentLocale->code);
-    $field = collect([
-        ...$field,
-        'isVisible' => true,
-    ])->map(function ($value, $key) use($coreConfigRepository) {
-        if ($key == 'options') {
-            return collect($coreConfigRepository->getOptions($value))->map(fn ($option) => [
-                'title' => trans($option['title']),
-                'value' => $option['value'],
-            ])->toArray();
-        }
-        return $value;
-    })->toArray();
-    if (! empty($field['depends'])) {
-        [$fieldName, $fieldValue] = explode(':' , $field['depends']);
-        $dependNameKey = $item['key'] . '.' . $fieldName;
-    }
+    $value = system_config()->getConfigData($field->getNameKey(), $currentChannel->code, $currentLocale->code);
@endphp

<input
    type="hidden"
    name="keys[]"
-    value="{{ json_encode($item) }}"
+    value="{{ json_encode($child) }}"
/>

-<v-configurable
-    channel-count="{{ core()->getAllChannels()->count() }}"
-    channel-locale="{{ $channelLocaleInfo }}"
-    current-channel="{{ $currentChannel }}"
-    current-locale="{{ $currentLocale }}"
-    depend-name="{{ isset($field['depends']) ? $coreConfigRepository->getNameField($dependNameKey) : ''}}"
-    field-data="{{ json_encode($field) }}"
-    info="{{ trans($field['info'] ?? '') }}"
-    is-require="{{ $isRequired }}"
-    label="{{ trans($field['title']) }}"
-    name="{{ $name }}"
-    src="{{ Storage::url(core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)) }}"
-    validations="{{ $validations }}"
-    value="{{ core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? '' }}"
->
-    <div class="mb-4">
+<div class="mb-4 last:!mb-0">
+    <v-configurable
+        name="{{ $field->getNameField() }}"
+        value="{{ $value }}"
+        label="{{ trans($field->getTitle()) }}"
+        info="{{ trans($field->getInfo()) }}"
+        validations="{{ $field->getValidations() }}"
+        is-require="{{ $field->isRequired() }}"
+        depend-name="{{ $field->getDependFieldName() }}"
+        src="{{ Storage::url($value) }}"
+        field-data="{{ json_encode($field) }}"
+        channel-count="{{ $channels->count() }}"
+        current-channel="{{ $currentChannel }}"
+        current-locale="{{ $currentLocale }}"
+    >
        <div class="shimmer mb-1.5 h-4 w-24"></div>

        <div class="shimmer flex h-[42px] w-full rounded-md"></div>
-    </div>
+    </v-configurable>
+</div>
-</v-configurable>
```

<a name="admin-customized-datagrid-parameters-updated"></a>
#### Admin Customized Datagrid Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid `header` was customized using parameters such as `columns`, `records`, `sortPage`, `selectAllRecords`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

```diff
- <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
-    <!-- Header customization code -->
- </template>
+ <template #header="{
+    isLoading,
+    available,
+    applied,
+    selectAll,
+    sort,
+    performAction
+ }">
+    <!-- Updated header customization code -->
+ </template>
```

2. Previously, the data grid `body` was customized using parameters such as `columns`, `records`, `setCurrentSelectionMode`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

```diff
- <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading }">
-    <!-- Updated body customization code -->
- </template>
+ <template #body="{
+    isLoading,
+    available,
+    applied,
+    selectAll,
+    sort,
+    performAction
+ }">
+    <!-- Updated body customization code -->
+ </template>
```



<a name="checkout"></a>
### Checkout

<a name="the-checkout-tables-schema-updates"></a>
#### The Checkout Tables Schema Updates

**Impact Probability: Medium**

1: New columns related to managing inclusive tax have been added to the `cart` table.

```diff
+ Schema::table('cart', function (Blueprint $table) {
+     $table->decimal('shipping_amount', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_shipping_amount', 12, 4)->default(0)->after('shipping_amount');
+ 
+     $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0)->after('base_shipping_amount');
+     $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0)->after('shipping_amount_incl_tax');
+ 
+     $table->decimal('sub_total_incl_tax', 12, 4)->default(0)->after('base_shipping_amount_incl_tax');
+     $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0)->after('sub_total_incl_tax');
+ });
```

2: New columns related to managing inclusive tax have been added to the `cart_items` table.

```diff
+ Schema::table('cart_items', function (Blueprint $table) {
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+ 
+     $table->decimal('total_incl_tax', 12, 4)->default(0)->after('base_price_incl_tax');
+     $table->decimal('base_total_incl_tax', 12, 4)->default(0)->after('total_incl_tax');
+ 
+     $table->string('applied_tax_rate')->nullable()->after('base_total_incl_tax');
+ });
```

3: New columns related to managing inclusive shipping tax have been added to the `cart_shipping_rates` table.

```diff
+ Schema::table('cart_shipping_rates', function (Blueprint $table) {
+     $table->decimal('tax_percent', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('tax_amount', 12, 4)->default(0)->after('tax_percent');
+     $table->decimal('base_tax_amount', 12, 4)->default(0)->after('tax_amount');
+ 
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_tax_amount');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+ 
+     $table->string('applied_tax_rate')->nullable()->after('base_price_incl_tax');
+ });
```

<a name="the-cart-model"></a>
#### The `Webkul\Checkout\Models\Cart` model

**Impact Probability: Medium**

1. The `addresses` method has been removed. It was previously utilized in the `billing_address` and `shipping_address` methods. We have now revised both the `billing_address` and `shipping_address` relationships, rendering the addresses method unnecessary.

```diff
- public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
- {
-     return $this->hasMany(CartAddressProxy::modelClass());
- }
```

2. We have revised the `billing_address` method to return a HasOne object instead of a HasMany object. Additionally, we have removed the `getBillingAddressAttribute` accessor, as the `billing_address` method now methods identically to it.

```diff
- public function billing_address(): \Illuminate\Database\Eloquent\Relations\HasMany
- {
-     return $this->addresses()
-         ->where('address_type', CartAddress::ADDRESS_TYPE_BILLING);
- }
+ public function billing_address(): \Illuminate\Database\Eloquent\Relations\HasOne
+ {
+     return $this->hasOne(CartAddressProxy::modelClass())->where('address_type', CartAddress::ADDRESS_TYPE_BILLING);
+ }

- public function getBillingAddressAttribute()
- {
-     return $this->billing_address()->first();
- }
```

3. We have revised the `shipping_address` method to return a HasOne object instead of a HasMany object. Additionally, we have removed the `getShippingAddressAttribute` accessor, as the `shipping_address` method now methods identically to it.

```diff
- public function shipping_address(): \Illuminate\Database\Eloquent\Relations\HasMany
- {
-     return $this->addresses()
-         ->where('address_type', CartAddress::ADDRESS_TYPE_SHIPPING);
- }
+ public function shipping_address(): \Illuminate\Database\Eloquent\Relations\HasOne
+ {
+     return $this->hasOne(CartAddressProxy::modelClass())->where('address_type', CartAddress::ADDRESS_TYPE_SHIPPING);
+ }

- public function getShippingAddressAttribute()
- {
-     return $this->shipping_address()->first();
- }
```

4. We have updated the `shipping_rates` method to return a HasMany object instead of a HasManyThrough object, as shipping rates are now directly associated with the cart.

```diff
- public function shipping_rates(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
- {
-     return $this->hasManyThrough(CartShippingRateProxy::modelClass(), CartAddressProxy::modelClass(), 'cart_id', 'cart_address_id');
- }
+ public function shipping_rates(): \Illuminate\Database\Eloquent\Relations\HasMany
+ {
+     return $this->hasMany(CartShippingRateProxy::modelClass());
+ }
```

<a name="removed-cart-traits"></a>
#### Removed Cart Traits

**Impact Probability: Low**

All methods from the following traits have been relocated to the `Webkul\Checkout\Cart` class, and the traits have been removed.

<div class="content-list" markdown="1">

- `Webkul\Checkout\Traits\CartCoupons` trait
- `Webkul\Checkout\Traits\CartTools` trait
- `Webkul\Checkout\Traits\CartValidators` trait

</div>

<a name="the-cart-class"></a>
#### The `Webkul\Checkout\Cart` class

**Impact Probability: High**

1. The `initCart` method now accepts an optional `Webkul\Customer\Models\Customer` model instance and initializes the cart based on this parameter.

```diff
- public function initCart()
+ public function initCart(?CustomerContract $customer = null): void

```

2. The `getCart` method now exclusively returns the cart itself, meaning it will no longer retrieve the current cart.

3. We have updated the `addProduct` method to accept the `Webkul\Product\Models\Product` model instance as the first parameter instead of the product ID.

```diff

- public function addProduct($productId, $data)
+ public function addProduct(ProductContract $product, array $data): Contracts\Cart|\Exception

```

4. We've renamed the `create` method to `createCart`

```diff
- public function create($data)
+ public function createCart(array $data): ?Contracts\Cart
```

5. The `emptyCart` method has been renamed to `removeCart`, and it now accepts a cart model instance.

```diff
- public function emptyCart()
+ public function removeCart(Contracts\Cart $cart): void
```

6. We have introduced a new method called `refreshCart`. This method retrieves a refreshed cart instance from the database.

```diff
+ public function refreshCart(): void
```

7. The `putCart` method previously found in CartTools has been eliminated. It is now managed within the setCart method in the `Webkul\Checkout\Cart` class.

```diff
- public function putCart($cart)
```

8. We've enhanced the mergeCart method to now accept an instance of the `Webkul\Customer\Models\Customer` model. Previously, we merged the guest cart with the logged-in customer's cart by fetching the current customer within this method. However, it now directly accepts the `Webkul\Customer\Models\Customer` model instance.

```diff
- public function mergeCart(): void
+ public function mergeCart(CustomerContract $customer): void
```

9. The `saveCustomerDetails` method has been renamed to `setCustomerPersonnelDetails`

```diff
- public function saveCustomerDetails(): void
+ public function setCustomerPersonnelDetails(): void
```

10. We have removed the following methods and relocated the cart data transformation to a separate resource class named `Webkul\Sales\Transformers\OrderResource`.

```diff
- public function prepareDataForOrder(): array

- public function prepareDataForOrderItem(): array

- public function toArray(): array
```

11. The `collectTotals` method now returns a self instance instead of void, allowing for chaining multiple methods with the Cart facade.

```diff
- public function collectTotals(): void
+ public function collectTotals(): self
```



<a name="datagrid"></a>
### DataGrid

<a name="the-datagrid-class"></a>
#### The `Webkul\DataGrid\DataGrid` Class

**Impact Probability: Medium**

1. We have made some of the methods in this class protected. Here are the methods, please have a look.

```diff
- public function validatedRequest(): array
+ protected function validatedRequest(): array

- public function processRequestedFilters(array $requestedFilters)
+ protected function processRequestedFilters(array $requestedFilters)

- public function processRequestedSorting($requestedSort)
+ protected function processRequestedSorting($requestedSort)

- public function processRequestedPagination($requestedPagination): LengthAwarePaginator
+ protected function processRequestedPagination($requestedPagination): LengthAwarePaginator

- public function processRequest(): void
+ protected function processRequest(): void

- public function sanitizeRow($row): \stdClass
+ protected function sanitizeRow($row): \stdClass

- public function formatData(): array
+ protected function formatData(): array

- public function prepare(): void
+ protected function prepare(): void
```

2. We have deprecated the 'toJson' method. Instead of 'toJson', please use the 'process' method.

```diff
- app(AttributeDataGrid::class)->toJson();
+ datagrid(AttributeDataGrid::class)->process();
```

3. The setExportFile method will now only accept the file format:

```diff
- public function setExportFile($records, $format = 'csv') 
+ public function setExportFile($format = 'csv')
```

<a name="the-column-class"></a>
#### The `Webkul/DataGrid/src/Column` Class

**Impact Probability: Medium**

1. The `Column` class has undergone a change in how it stores the fully qualified column name. The property name and its access level have been modified.

```diff
- public $databaseColumnName;
+ protected $columnName;

- public string $index;
+ protected string $index;

- public string $label;
+ protected string $label;

- public string $type;
+ protected string $type;

- public bool $searchable = false;
+ protected bool $searchable = false;

- public bool $filterable = false,
+ protected bool $filterable = false;

- public bool $sortable = false,
+ protected bool $sortable = false;

- public mixed $closure = null,
+ protected mixed $closure = null;
```

2. The setDatabaseColumnName method has been renamed to setColumnName  and the getDatabaseColumnName method has been renamed to geDatabaseColumnName.

```diff
- public function setDatabaseColumnName(mixed $databaseColumnName = null)
+ public function setColumnName(mixed $columnName): void

- public function getDatabaseColumnName(): mixed
+ public function getColumnName(): mixed
```

3. The `getRangeOptions` method has been removed from `Column` class and its functionality is now managed by the `DateRangeOptionEnum` class located in `Webkul/DataGrid/src/Enums/DateRangeOptionEnum.php` through the `options`  method.

```diff
- public function getRangeOptions(string $format = 'Y-m-d'): array
+ public static function options(string $format = 'Y-m-d H:i:s'): array
```

4. The following methods have been removed from the backend and their functionalities are now managed from the front end:

```diff
- public function setFormInputType(string $formInputType): void

- public function getFormInputType(): ?string

- public function setFormOptions(array $formOptions): void

- public function getFormOptions(): ?array

- public function getBooleanOptions(): array
```

5. The `Column` class constructor now accepts a single array of properties instead of individual parameters.

```diff
- public function __construct(
-    public string $index,
-    public string $label,
-    public string $type,
-    public ?array $options = null,
-    public bool $searchable = false,
-    public bool $filterable = false,
-    public bool $sortable = false,
-    public mixed $closure = null,
- ) {
-      $this->init();
- }
+ public function __construct(array $column)
+ {
+   $this->init($column);
+ }
```

6. The column types are now updated The available column types are now defined as follows:`string`, `integer`, `float`, `boolean`, `date`, `datetime`, `aggregate`.
And the available FilterType `filterable_type` are now defined as follows: `dropdown`,`date_range`,`datetime_range`.   

```diff
- $this->addColumn([
-    'index'      => 'attribute_family',
-    'label'      => trans('admin::app.catalog.products.index.datagrid.attribute-family'),
-    'type'       => 'dropdown',
-    'options'    => [
-        'type' => 'basic',
-        'params' => [
-            'options' => $this->attributeFamilyRepository->all(['name as label', 'id as value'])->toArray(),
-        ],
-    ],
-    'searchable' => false,
-    'filterable' => true,
-    'sortable'   => false,
- ]); 
+ $this->addColumn([
+    'index'              => 'attribute_family',
+    'label'              => trans('admin::app.catalog.products.index.datagrid.attribute-family'),
+    'type'               => 'string',
+    'filterable'         => true,
+    'filterable_type'    => 'dropdown',
+    'filterable_options' => $this->attributeFamilyRepository->all(['name as label', 'id as value'])->toArray(),
+ ]);
```

7. By default, the properties `searchable`, `filterable`, and `sortable` will be false if you need to activate them, set them to `true`.

<a name="the-datagrid-export-class"></a>
#### The `Webkul\Admin\Exports\DataGridExport` Class

**Impact Probability: Medium**

1. This class will now accept the full DataGrid instance instead of the records to enhance the export features:

```diff
- public function __construct(protected $gridData = [])
+ public function __construct(protected DataGrid $datagrid)
```



<a name="notification"></a>
### Notification

<a name="the-notification-repository"></a>
#### The `Webkul\Notification\Repositories\NotificationRepository` Repository

**Impact Probability: Medium**

1. We've made revisions to the `getAll` method to allow for optional parameters.

```diff
- public function getAll()
+ public function getAll(array $params = [])
```



<a name="product"></a>
### Product

<a name="the-elastic-search-repository"></a>
#### The `Webkul\Product\Repositories\ElasticSearchRepository` Repository

**Impact Probability: Medium**

1. We have enhanced the `search` method to accept two arguments. The first argument is an array containing the search parameters (e.g., category_id, etc.), while the second argument is an array containing the options.

```diff
- public function search($categoryId, $options)
+ public function search(array $params, array $options): array
```

2.  We've enhanced the `getFilters` method to now accept an array of parameters, as request parameters will originate from the search method itself.

```diff
- public function getFilters()
+ public function getFilters(array $params): array
```

<a name="the-product-repository"></a>
#### The `Webkul\Product\Repositories\ProductRepository` Repository

**Impact Probability: Medium**

1. We've made revisions to the `getAll` method to allow for optional search parameters.

```diff
- public function getAll()
+ public function getAll(array $params = [])
```

Previously, this method returned only active products. Now, it returns all products by default. To retrieve active products, you need to pass `status` and `visible_individually` as `1`. Additionally, you can pass `channel_id` to filter products specific to a channel. You can also specify the search engine by using `setSearchEngine` and passing either `database` or `elastic`.

```diff
- $products = $this->productRepository->getAll();

+ $products = $this->productRepository
+     ->setSearchEngine('database')
+     ->getAll(array_merge(request()->query(), [
+         'channel_id'           => core()->getCurrentChannel()->id,
+         'status'               => 1,
+         'visible_individually' => 1,
+     ]));
```

2. We've made revisions to the `searchFromDatabase` method to allow for optional search parameters.

```diff
- public function searchFromDatabase()
+ public function searchFromDatabase(array $params = [])
```

3. We've made revisions to the `searchFromElastic` method to allow for optional search parameters.

```diff
- public function searchFromElastic()
+ public function searchFromElastic(array $params = [])
```

<a name="the-product-type-class"></a>
#### The Product Types Classes Updates

If you've implemented your own product type or overridden existing type classes, you'll need to update the following methods to include inclusive tax management.

**Impact Probability: Low**

1: The `evaluatePrice` and `getTaxInclusiveRate` methods have been removed. Please update your `getProductPrices` method accordingly to no longer use these methods.

```diff
- public function evaluatePrice($price)

- public function getTaxInclusiveRate($totalPrice)
```

2: Please update your `prepareForCart` and `validateCartItem` methods to include the `*_incl_tax` columns for managing inclusive tax calculation for your product type. You can refer to the `Webkul\Product\Type\AbstractType` class and adjust your class accordingly.

<a name="the-configurable-type-class"></a>
#### The `Webkul\Product\Type\Configurable` Class

**Impact Probability: High**

1. We've removed the following methods from the `Webkul\Product\Type\Configurable` class as we no longer support default configurable variants.

```diff
- public function getDefaultVariant()

- public function getDefaultVariantId()

- public function setDefaultVariantId()

- public function updateDefaultVariantId()
```

<a name="the-elastic-indexing"></a>
#### The product Elastic Search indexing

**Impact Probability: Medium**

Previously, Elastic Search was used only on the frontend and not in the admin section. For large catalogs, this caused the Product datagrid to be very slow. To address this issue, we have now introduced Elastic Search in the admin section as well.

To make Elastic Search compatible with the admin section, some changes were necessary. Previously, only active products were indexed in Elastic Search. Now, all products are indexed with additional keys/information.

Please run the following command to refresh the Elastic Search indices:


```diff
php artisan indexer:index --type=elastic
```

<a name="Sales"></a>
### Sales

<a name="the-sales-tables-schema-updates"></a>
#### The Sales Tables Schema Updates

**Impact Probability: Medium**

1: New columns related to managing inclusive tax have been added to the `orders` table.

```diff
+ Schema::table('orders', function (Blueprint $table) {
+     $table->decimal('shipping_tax_amount', 12, 4)->default(0)->after('base_shipping_discount_amount');
+     $table->decimal('base_shipping_tax_amount', 12, 4)->default(0)->after('shipping_tax_amount');
+ 
+     $table->decimal('shipping_tax_refunded', 12, 4)->default(0)->after('base_shipping_tax_amount');
+     $table->decimal('base_shipping_tax_refunded', 12, 4)->default(0)->after('shipping_tax_refunded');
+ 
+     $table->decimal('sub_total_incl_tax', 12, 4)->default(0)->after('base_shipping_tax_refunded');
+     $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0)->after('sub_total_incl_tax');
+ 
+     $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0)->after('base_sub_total_incl_tax');
+     $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0)->after('shipping_amount_incl_tax');
+ });
```

2: New columns related to managing inclusive tax have been added to the `order_items` table.

```diff
+ Schema::table('order_items', function (Blueprint $table) {
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_tax_amount_refunded');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+
+     $table->decimal('total_incl_tax', 12, 4)->default(0)->after('base_price_incl_tax');
+     $table->decimal('base_total_incl_tax', 12, 4)->default(0)->after('total_incl_tax');
+ });
```

3: New columns related to managing inclusive tax have been added to the `invoices` table.

```diff
+ Schema::table('invoices', function (Blueprint $table) {
+     $table->decimal('shipping_tax_amount', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_shipping_tax_amount', 12, 4)->default(0)->after('shipping_tax_amount');
+ 
+     $table->decimal('sub_total_incl_tax', 12, 4)->default(0)->after('base_shipping_tax_amount');
+     $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0)->after('sub_total_incl_tax');
+ 
+     $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0)->after('base_sub_total_incl_tax');
+     $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0)->after('shipping_amount_incl_tax');
+ });
```

4: New columns related to managing inclusive tax have been added to the `invoice_items` table.

```diff
+ Schema::table('invoice_items', function (Blueprint $table) {
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+     $table->decimal('total_incl_tax', 12, 4)->default(0)->after('base_price_incl_tax');
+     $table->decimal('base_total_incl_tax', 12, 4)->default(0)->after('total_incl_tax');
+ });
```

5: New columns related to managing inclusive tax have been added to the `refunds` table.

```diff
+ Schema::table('refunds', function (Blueprint $table) {
+     $table->decimal('shipping_tax_amount', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_shipping_tax_amount', 12, 4)->default(0)->after('shipping_tax_amount');
+ 
+     $table->decimal('sub_total_incl_tax', 12, 4)->default(0)->after('base_shipping_tax_amount');
+     $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0)->after('sub_total_incl_tax');
+ 
+     $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0)->after('base_sub_total_incl_tax');
+     $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0)->after('shipping_amount_incl_tax');
+ });
```

6: New columns related to managing inclusive tax have been added to the `refund_items` table.

```diff
+ Schema::table('refund_items', function (Blueprint $table) {
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_discount_amount');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+     $table->decimal('total_incl_tax', 12, 4)->default(0)->after('base_price_incl_tax');
+     $table->decimal('base_total_incl_tax', 12, 4)->default(0)->after('total_incl_tax');
+ });
```

7: New columns related to managing inclusive tax have been added to the `shipment_items` table.

```diff
+ Schema::table('shipment_items', function (Blueprint $table) {
+     $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_total');
+     $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
+ });
```

**Impact Probability: Low**

<a name="the-order-item-repository"></a>
#### The `Webkul\Sales\Repositories\OrderItemRepository` Repository

1. The `create` method has been removed. Previously, it was overridden to set product_id and product_type for the polymorphic relation between `Webkul\Sales\Models\OrderItem` and `Webkul\Product\Models\Product`. Now, this information is obtained from the cart transformer `Webkul\Sales\Transformers\OrderResource`.

```diff
- public function create(array $data)
```



<a name="shop"></a>
### Shop

<a name="shop-customized-datagrid-parameters-updated"></a>
####  Shop Customized Datagrid Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid `header` was customized using parameters such as `columns`, `records`, `sortPage`, `selectAllRecords`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

```diff
- <template #header="{ columns, records, sortPage, selectAllRecords, applied, isLoading}">
-    <!-- Header customization code -->
- </template>
+ <template #header="{
+    isLoading,
+    available,
+    applied,
+    selectAll,
+    sort,
+    performAction
+ }">
+    <!-- Updated header customization code -->
+ </template>
```

2. Previously, the data grid `body` was customized using parameters such as `columns`, `records`, `setCurrentSelectionMode`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

```diff
- <template #body="{ columns, records, setCurrentSelectionMode, applied, isLoading }">
-    <!-- Updated body customization code -->
- </template>
+ <template #body="{
+    isLoading,
+    available,
+    applied,
+    selectAll,
+    sort,
+    performAction
+ }">
+    <!-- Updated body customization code -->
+ </template>
```

<a name="shop-event-updates"></a>
#### Shop Event Updates

**Impact Probability: Medium**

##### Shop Event Parameter Updated
1. The event data previously containing an email address has been updated to include an instance of the `Webkul\Customer\Models\Customer` model.

```diff
- Event::dispatch('customer.after.login', $loginRequest->get('email'));
+ Event::dispatch('customer.after.login', auth()->guard()->user());
```

##### Shop Event Inside Head Updated

**Impact Probability: High**

1. The event that was previously added in Shop has now been updated in the new format. You can now directly add your own custom elements inside the <head> tag.

```diff
+ {!! view_render_event('bagisto.shop.layout.head.before') !!}

- {!! view_render_event('bagisto.shop.layout.head') !!}
+ {!! view_render_event('bagisto.shop.layout.head.after') !!}
```

<a name="renamed-shop-api-routes-names"></a>
#### Renamed Shop API Route Names

**Impact Probability: Low**

1. The routes names have been renamed for consistency in the `packages/Webkul/Shop/src/Routes/api.php` route file.

```diff
- Route::get('', 'index')->name('api.shop.customers.account.addresses.index');
+ Route::get('', 'index')->name('shop.api.customers.account.addresses.index');

- Route::post('', 'store')->name('api.shop.customers.account.addresses.store');
+ Route::post('', 'store')->name('shop.api.customers.account.addresses.store');

- Route::put('edit/{id?}', 'update')->name('api.shop.customers.account.addresses.update');
+ Route::put('edit/{id?}', 'update')->name('shop.api.customers.account.addresses.update');
```

<a name="renamed-shop-controller-method-names"></a>
#### Renamed Shop Controller Method Names

**Impact Probability: Low**

1. The controller action names for the following routes have been renamed to ensure consistency with the `packages/Webkul/Shop/src/Routes/customer-routes.php` route file.

```diff
- Route::get('', 'show')->name('shop.customer.session.index');
+ Route::get('', 'index')->name('shop.customer.session.index');

- Route::post('', 'create')->name('shop.customer.session.create');
+ Route::post('', 'store')->name('shop.customer.session.create');

```

<a name="the-shop-api-response-updates"></a>
#### Shop API Response Updates

**Impact Probability: High**

1. The response for the Shop route `shop.api.checkout.cart.index` or `/api/checkout/cart` API has been updated. If you are consuming this API, please make the necessary changes to accommodate the updated response format.

```diff
{
    data: {
        "id": 243,
        "is_guest": 0,
        "customer_id": 1,
        "items_count": 1,
        "items_qty": 1,
-       "base_tax_amounts": [
-           "$0.00"
-       ],
+       "applied_taxes": {
+           "US-AL (10%)": "$10.00"
+       },
-       "base_tax_total": 10,
        "tax_total": 10,
        "formatted_tax_total": "$10.00",
-       "base_sub_total": 100,
        "sub_total": 100,
        "formatted_sub_total": "$100.00",
+       "sub_total_incl_tax": 110,
+       "formatted_sub_total_incl_tax": "$110.00",
        "coupon_code": null,
-       "base_discount_amount": 0,
-       "formatted_base_discount_amount": "$0.00",
        "discount_amount": 0,
        "formatted_discount_amount": "$0.00",
-       "selected_shipping_rate_method": "",
-       "selected_shipping_rate": "$0.00",
+       "shipping_method": "flatrate_flatrate",
+       "shipping_amount": 5,
+       "formatted_shipping_amount": "$5.00",
+       "shipping_amount_incl_tax": "5.0000",
+       "formatted_shipping_amount_incl_tax": "$5.00",
-       "base_grand_total": 115,
        "grand_total": 115,
        "formatted_grand_total": "$115.00",
        "have_stockable_items": true,
        "payment_method": null,
        "payment_method_title": null
        "billing_address": null,
        "shipping_address": null,
        "items": [
            {
                "id": 544,
                "quantity": 1,
                "type": "configurable",
                "name": "OmniHeat Men's Solid Hooded Puffer Jacket",
                "price": "100.0000",
                "formatted_price": "$100.00",
+               "price_incl_tax": "110.0000",
+               "formatted_price_incl_tax": "$110.00",
                "total": "100.0000",
                "formatted_total": "$100.00",
+               "total_incl_tax": "110.0000",
+               "formatted_total_incl_tax": "$110.00",
+               "discount_amount": "0.0000",
+               "formatted_discount_amount": "$0.00",
                "options": [
                    {
                        "option_id": 7,
                        "option_label": "M",
                        "attribute_name": "Size"
                    },
                    {
                        "option_id": 2,
                        "option_label": "Green",
                        "attribute_name": "Color"
                    }
                ],
                "base_image": {
                    "small_image_url": "http://localhost/laravel/bagisto/public/cache/small/product/10/CvW2Q3eP4HNUKpQCjyrMUvnwEypVQZCf1VcLAnH4.webp",
                    "medium_image_url": "http://localhost/laravel/bagisto/public/cache/medium/product/10/CvW2Q3eP4HNUKpQCjyrMUvnwEypVQZCf1VcLAnH4.webp",
                    "large_image_url": "http://localhost/laravel/bagisto/public/cache/large/product/10/CvW2Q3eP4HNUKpQCjyrMUvnwEypVQZCf1VcLAnH4.webp",
                    "original_image_url": "http://localhost/laravel/bagisto/public/cache/original/product/10/CvW2Q3eP4HNUKpQCjyrMUvnwEypVQZCf1VcLAnH4.webp"
                },
                "product_url_key": "omniheat-mens-solid-hooded-puffer-jacket"
            }
        ]
    }
}
```

2. The response for the Shop route `shop.api.checkout.cart.store` API has been updated. If you are consuming this API, please make the necessary changes. we have refined the exception handling to provide more specific error responses and HTTP_BAD_REQUEST status code, ensuring better feedback for users.

```diff
- catch (\Exception $exception) {
-   return new JsonResource([
-       'redirect_uri' => route('shop.product_or_category.index', $product->product->url_key),
-       'message'      => $exception->getMessage(),
-   ]);
- }

+ catch (\Exception $exception) {
+    return response()->json([
+        'redirect_uri' => route('shop.product_or_category.index', $product->url_key),
+        'message'      => $exception->getMessage(),
+    ], Response::HTTP_BAD_REQUEST);
+ }
```

3. The response for the Shop route `shop.api.categories.index` or `/api/categories` API has been updated. If you are consuming this API, please make the necessary changes to accommodate the updated response format.

```diff
{
    "data": [
        {
            "id": 2,
            "parent_id": 1,
            "name": "Men",
            "slug": "men",
            "status": 1,
            "position": 1,
            "display_mode": "products_and_description",
            "description": "<p>Men</p>",
-           "images": {
-               "banner_url": null,
-               "logo_url": "https://demo.bagisto.com/bagisto-common/storage/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp"
-           },
+           "logo": {
+               "small_image_url": "http://localhost/laravel/bagisto/public/cache/small/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "medium_image_url": "http://localhost/laravel/bagisto/public/cache/medium/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "large_image_url": "http://localhost/laravel/bagisto/public/cache/large/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "original_image_url": "http://localhost/laravel/bagisto/public/cache/original/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp"
+           },
+           "banner": {
+               "small_image_url": "http://localhost/laravel/bagisto/public/cache/small/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "medium_image_url": "http://localhost/laravel/bagisto/public/cache/medium/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "large_image_url": "http://localhost/laravel/bagisto/public/cache/large/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp",
+               "original_image_url": "http://localhost/laravel/bagisto/public/cache/original/category/2/OYsuHioryn5KrOE7p8wQ2hQ3BReXY5CSbDzhvEk8.webp"
+           },
            "meta": {
                "title": "",
                "keywords": "",
                "description": ""
            },
            "translations": [
                {
                    "id": 2,
                    "category_id": 2,
                    "name": "Men",
                    "slug": "men",
                    "url_path": "men",
                    "description": "<p>Men</p>",
                    "meta_title": "",
                    "meta_description": "",
                    "meta_keywords": "",
                    "locale_id": 1,
                    "locale": "en"
                }
            ],
            "additional": []
        }
    ]
}
```

4. The response for the Shop route `shop.api.products.index` or `/api/products` API has been updated. If you are consuming this API, please make the necessary changes to accommodate the updated response format.

```diff
{
    "data": [
        {
            "id": 174,
            "sku": "COMPLETELOOKSET2023",
            "name": "All-in-One Smart Casual Outfit Set",
            "description": "All-in-One Smart Casual Outfit Set",
            "url_key": "all-in-one-smart-casual-outfit-set",
            "base_image": {
                "small_image_url": "http://localhost/laravel/bagisto/public/cache/small/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                "medium_image_url": "http://localhost/laravel/bagisto/public/cache/medium/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                "large_image_url": "http://localhost/laravel/bagisto/public/cache/large/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                "original_image_url": "http://localhost/laravel/bagisto/public/cache/original/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp"
            },
            "images": [
                {
                    "small_image_url": "http://localhost/laravel/bagisto/public/cache/small/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                    "medium_image_url": "http://localhost/laravel/bagisto/public/cache/medium/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                    "large_image_url": "http://localhost/laravel/bagisto/public/cache/large/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp",
                    "original_image_url": "http://localhost/laravel/bagisto/public/cache/original/product/174/6zgmyY14TQ2WqCxEEdENs8tSfI6bAJbq0bjljQOq.webp"
                }
            ],
            "is_new": true,
            "is_featured": true,
            "on_sale": true,
            "is_saleable": true,
            "is_wishlist": true,
            "min_price": "$168.96",
            "prices": {
                "from": {
                    "regular": {
                        "price": "176.9600",
                        "formatted_price": "$176.96"
                    },
                    "final": {
                        "price": "168.9600",
                        "formatted_price": "$168.96"
                    }
                },
                "to": {
                    "regular": {
                        "price": "176.9600",
                        "formatted_price": "$176.96"
                    },
                    "final": {
                        "price": "168.9600",
                        "formatted_price": "$168.96"
                    }
                }
            },
            "price_html": "<div class=\"grid gap-1.5\">\n<p class=\"flex items-center gap-4 max-sm:text-lg\">\n<span\nclass=\"text-zinc-500 line-through max-sm:text-base\"\n    aria-label=\"$176.96\"\n>\n$176.96\n</span>\n\n$168.96\n</p>\n\n</div>",
-           "avg_ratings": 4.5,
+           "ratings": {
+               "average": "2.0",
+               "total": 2
+           }
        }
    ]
}
```

<a name="the-admin-shop-menu-updates"></a>
#### Admin and Shop Menu Updates

**Impact Probability: High**

1. Previously, the composeView method included logic to share a dynamically generated menu structure with several Blade views in the admin interface. This logic has been removed. Here’s a detailed breakdown of what was removed:

#### For `Admin` package.

```diff
class AdminServiceProvider extends ServiceProvider
{
    protected function composeView()
    {
-       view()->composer([
-           'admin::components.layouts.header.index',
-           'admin::components.layouts.sidebar.index',
-           'admin::components.layouts.tabs',
-       ], function ($view) {
-           $tree = Tree::create();
- 
-           foreach (config('menu.admin') as $index => $item) {
-               if (! bouncer()->hasPermission($item['key'])) {
-                   continue;
-               }
-
-               $tree->add($item, 'menu');
-           }
-
-           $tree->items = $tree->removeUnauthorizedUrls();
-
-           $tree->items = core()->sortItems($tree->items);
-
-           $view->with('menu', $tree);
-       });

        view()->composer([
            'admin::settings.roles.create',
            'admin::settings.roles.edit',
        ], function ($view) {
            $view->with('acl', $this->createACL());
        });
    }
}
```
#### For `Shop` package.

```diff
class ShopServiceProvider extends ServiceProvider
{
-   protected function composeView()
-   {
-       view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
-           $tree = Tree::create();
-           foreach (config('menu.customer') as $item) {
-               $tree->add($item, 'menu');
-           }
-           $tree->items = core()->sortItems($tree->items);
-           $view->with('menu', $tree);
-       });
-   }
}
```

#### How to use it?

##### For `Admin` package.

```diff
-    @foreach ($menu->items as $menuItem)
+    @foreach (menu()->getItems('admin') as $menuItem)
        <div
            class="px-4 group/item {{ $menu->getActive($menuItem) ? 'active' : 'inactive' }}"
            onmouseenter="adjustSubMenuPosition(event)"
        >
        ...
    @endforeach

```

##### For `Shop` package.

```diff
-    @foreach ($menu->items as $menuItem)
+    @foreach (menu()->getItems('customer') as $menuItem)
        <div class="max-md:rounded-md max-md:border max-md:border-b max-md:border-l-[1px] max-md:border-r max-md:border-t-0 max-md:border-zinc-200">
            <v-account-navigation>
        ...
    @endforeach

```

The getItems() methods of the menu() facade accept different areas of the menu. For example, for the admin area, you need to provide the config name of the menu, whereas for the shop area, you should provide the name 'customer'.


<a name="the-admin-acl-updates"></a>
#### Admin ACL Updates

**Impact Probability: High**

1. Previously, the composeView method included logic to share a dynamically generated acl structure with several Blade views in the admin interface. This logic has been removed. Here’s a detailed breakdown of what was removed:


```diff
class AdminServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        ...

-        $this->registerACL();

        ...

        $this->app->register(EventServiceProvider::class);
    }

-   protected function composeView()
-   {
-       view()->composer([
-           'admin::settings.roles.create',
-           'admin::settings.roles.edit',
-       ], function ($view) {
-           $view->with('acl', $this->createACL());
-       });
-   }

-    protected function registerACL()
-    {
-        $this->app->singleton('acl', function () {
-            return $this->createACL();
-        });
-    }
-
-    protected function createACL()
-    {
-        static $tree;
-
-        if ($tree) {
-            return $tree;
-        }
-
-        $tree = Tree::create();
-
-        foreach (config('acl') as $item) {
-            $tree->add($item, 'acl');
-        }
-
-        $tree->items = core()->sortItems($tree->items);
-
-        return $tree;
-    }
}
```

#### How to use it?

##### Get all acl items.

```php
    $acl = acl()->getItems();
```

##### Get all roles.

```php 
    $roles = acl()->getRoles();
```

<a name="renamed-star-rating-blade"></a>
#### Renamed `star-rating.blade.php`

**Impact Probability: Low**

1. The file `packages/Webkul/Shop/src/Resources/views/components/products/star-rating.blade.php` has been renamed to the `packages/Webkul/Shop/src/Resources/views/components/products/ratings.blade.php`.


<a name="moved-coupon-blade"></a>
#### Moved `coupon.blade.php`

**Impact Probability: Low**

1. The file `packages/Webkul/Shop/src/Resources/views/checkout/cart/coupon.blade.php` has been relocated to the `packages/Webkul/Shop/src/Resources/views/checkout/coupon.blade.php` directory. This move was made because the file is included on both the checkout and cart pages.



<a name="tax"></a>
### Tax

<a name="moved-tax-helper-class"></a>
#### The `Webkul\Tax\Helpers\Tax` Class Moved

**Impact Probability: Low**

1: The `Webkul\Tax\Helpers\Tax` class has been replaced with `Webkul\Tax\Tax`. Now, the `Webkul\Tax\Tax` class is bound to the `Webkul\Tax\Facades\Tax` facade, and all static methods have been converted to normal methods. However, you can still access these methods as static methods using the `Webkul\Tax\Facades\Tax` facade.

```diff
- public static function isTaxInclusive(): bool
+ public function isInclusiveTaxProductPrices(): bool

- public static function getTaxRatesWithAmount(object $that, bool $asBase = false): array
+ public function getTaxRatesWithAmount(object $that, bool $asBase = false): array

- public static function getTaxTotal(object $that, bool $asBase = false): float

- public static function getDefaultAddress()
+ public function getDefaultAddress(): object

- public static function isTaxApplicableInCurrentAddress($taxCategory, $address, $operation)
+ public function isTaxApplicableInCurrentAddress($taxCategory, $address, $operation): void
```

2: The new class for handling shipping tax inclusion now includes two additional methods: `isInclusiveTaxShippingPrices` and `getShippingOriginAddress`.

```diff
+ public function isInclusiveTaxShippingPrices(): bool

+ public function getShippingOriginAddress(): object
```
