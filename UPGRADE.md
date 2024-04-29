# UPGRADE Guide

- [Upgrading To v2.2.0 From v2.1.0](#upgrade-2.2.0)

<a name="high-impact-changes"></a>
## High Impact Changes

<div class="content-list" markdown="1">

- [Updating Dependencies](#updating-dependencies)
- [The `Webkul\Checkout\Cart` class](#the-cart-class)

</div>

<a name="medium-impact-changes"></a>
## Medium Impact Changes

<div class="content-list" markdown="1">

- [The `Webkul\Checkout\Models\Cart` model](#the-cart-model)
- [The `Webkul\Product\Repositories\ElasticSearchRepository` Repository](#the-elastic-search-repository)
- [The `Webkul\Product\Repositories\ProductRepository` Repository](#the-product-repository)
- [The `Webkul\Sales\Repositories\OrderItemRepository` Repository](#the-order-item-repository)
- [Shop Event parameter updated](#event-parameter-updated)
- [Admin Customized Datagrid Header Parameters Updated](#admin-customized-datagrid-header-parameter-updated)
- [Admin Customized Datagrid Body Parameters updated](#admin-customized-datagrid-body-parameter-updated)
- [Shop Customized Datagrid Header Parameters Updated](#shop-customized-datagrid-header-parameter-updated)
- [Shop Customized Datagrid Body Parameters Updated](#shop-customized-datagrid-body-parameter-updated)

</div>

<a name="low-impact-changes"></a>
## Low Impact Changes

<div class="content-list" markdown="1">

- [Removed Cart Traits](#removed-cart-traits)
- [Moved `coupon.blade.php`](#moved-coupon-blade)
- [Renamed Shop API Route Names](#renamed-shop-api-routes-names)
- [Renamed Shop Controller Method Names](#renamed-shop-controller-method-names)

</div>


<a name="upgrade-2.2.0"></a>
## Upgrading To v2.2.0 From v2.1.0

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.



<a name="updating-dependencies"></a>
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



<a name="admin"></a>
### Admin

<a name="admin-customized-datagrid-header-parameter-updated"></a>
####  Admin Customized Datagrid Header Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid header was customized using parameters such as `columns`, `records`, `sortPage`, `selectAllRecords`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

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

<a name="admin-customized-datagrid-body-parameter-updated"></a>
####  Admin Customized Datagrid Body Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid body was customized using parameters such as `columns`, `records`, `setCurrentSelectionMode`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

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
+    <!-- Updated header customization code -->
+ </template>
```



<a name="checkout"></a>
### Checkout

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



<a name="shop-blade-updates"></a>
### Shop Blade Updates

<a name="moved-coupon-blade"></a>
#### Moved `coupon.blade.php`

**Impact Probability: Low**

1. The file `packages/Webkul/Shop/src/Resources/views/checkout/cart/coupon.blade.php` has been relocated to the `packages/Webkul/Shop/src/Resources/views/checkout/coupon.blade.php` directory. This move was made because the file is included on both the checkout and cart pages.



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



<a name="Sales"></a>
### Sales

**Impact Probability: Low**

<a name="the-order-item-repository"></a>
#### The `Webkul\Sales\Repositories\OrderItemRepository` Repository

1. The `create` method has been removed. Previously, it was overridden to set product_id and product_type for the polymorphic relation between `Webkul\Sales\Models\OrderItem` and `Webkul\Product\Models\Product`. Now, this information is obtained from the cart transformer `Webkul\Sales\Transformers\OrderResource`.

```diff
- public function create(array $data)
```



<a name="shop"></a>
### Shop

<a name="shop-customized-datagrid-header-parameter-updated"></a>
####  Shop Customized Datagrid Header Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid header was customized using parameters such as `columns`, `records`, `sortPage`, `selectAllRecords`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

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

<a name="shop-customized-datagrid-body-parameter-updated"></a>
#### Shop Customized Datagrid Body Parameters Updated

**Impact Probability: Medium**

1. Previously, the data grid body was customized using parameters such as `columns`, `records`, `setCurrentSelectionMode`, `applied`, and `isLoading`. However, with the latest updates, the parameter names have been revised for clarity and consistency across components.

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
+    <!-- Updated header customization code -->
+ </template>
```

<a name="event-parameter-updated"></a>
#### Shop Event parameter updated

**Impact Probability: Medium**

1. The event data previously containing an email address has been updated to include an instance of the `Webkul\Customer\Models\Customer` model.

```diff
- Event::dispatch('customer.after.login', $loginRequest->get('email'));
+ Event::dispatch('customer.after.login', auth()->guard()->user());
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