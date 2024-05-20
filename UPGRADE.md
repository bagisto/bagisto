# UPGRADE Guide

- [Upgrading To v2.2.0 From v2.1.0](#upgrade-2.2.0)

## High Impact Changes

- [Updating Dependencies](#updating-dependencies)
- [The `Webkul\Checkout\Cart` class](#the-cart-class)
- [The `Webkul\Product\Type\Configurable` class](#the-configurable-type-class)
- [Shop API Response Updates](#the-shop-api-response-updates)

## Medium Impact Changes

- [Admin Customized Datagrid Parameters Updated](admin-customized-datagrid-parameters-updated)
- [Admin Event Updates](#admin-event-updates)
- [The System Configuration Updates](#the-system-config-update)
- [The `Webkul\Checkout\Models\Cart` model](#the-cart-model)
- [The Checkout Tables Schema Updates](#the-checkout-tables-schema-updates)
- [The `Webkul\DataGrid\DataGrid` class](#the-datagrid-class)
- [The `Webkul\Product\Repositories\ElasticSearchRepository` Repository](#the-elastic-search-repository)
- [The `Webkul\Product\Repositories\ProductRepository` Repository](#the-product-repository)
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
- [Moved `coupon.blade.php`](#moved-coupon-blade)
- [Renamed Shop API Route Names](#renamed-shop-api-routes-names)
- [Renamed Shop Controller Method Names](#renamed-shop-controller-method-names)
- [Renamed Admin View Render Event Names](#renamed-admin-view-render-event-names)

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

2: The `repository` option has been removed from the `select` type field in the system configuration. Now, you can use `options` as a closure to populate select field options from the database. Here's an example of how to update the configuration array:

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
+       'options'    => function() {
+           return [
+               [
+                   'title' => 'admin::app.configuration.index.sales.taxes.categories.none',
+                   'value' => 0,
+               ],
+           ];
+       }
+   ]
}
```

In this example, the `repository` option has been replaced with `options`, which is defined as a closure returning an array of options. Adjust the closure to populate the select field options as needed.

3. The Inventory Stock Options configuration has been relocated to the Order Settings configuration, and the respective path for retrieving configuration values has been updated accordingly

```diff
- core()->getConfigData('catalog.inventory.stock_options.back_orders')
+ core()->getConfigData('sales.order_settings.stock_options.back_orders')
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

1. We have made some of the methods in this class private. Here are the methods, please have a look.

```diff
- public function validatedRequest(): array
+ private function validatedRequest(): array

- public function processRequestedFilters(array $requestedFilters)
+ private function processRequestedFilters(array $requestedFilters)

- public function processRequestedSorting($requestedSort)
+ private function processRequestedSorting($requestedSort)

- public function processRequestedPagination($requestedPagination): LengthAwarePaginator
+ private function processRequestedPagination($requestedPagination): LengthAwarePaginator

- public function processRequest(): void
+ private function processRequest(): void

- public function sanitizeRow($row): \stdClass
+ private function sanitizeRow($row): \stdClass

- public function formatData(): array
+ private function formatData(): array

- public function prepare(): void
+ private function prepare(): void
```

2. We have deprecated the 'toJson' method. Instead of 'toJson', please use the 'process' method.

```diff
- app(AttributeDataGrid::class)->toJson();
+ datagrid(AttributeDataGrid::class)->process();
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

**Impact Probability: Medium**

<a name="the-elastic-search-repository"></a>
#### The `Webkul\Product\Repositories\ElasticSearchRepository` Repository

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