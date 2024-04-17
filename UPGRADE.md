# UPGRADE Guide

- [Upgrading To v2.1.0 From v2.2.0](#upgrade-2.2.0)

<a name="high-impact-changes"></a>
## High Impact Changes

<div class="content-list" markdown="1">

- [Updating Dependencies](#updating-dependencies)
- [The `Webkul\Checkout\Models\Cart` model](#the-cart-model)
- [Removed Cart Traits](#removed-cart-traits)
- [The `Webkul\Checkout\Cart` class](#the-cart-class)
- [Moved `coupon.blade.php`](#moved-coupon-blade)
- [The `Webkul\Product\Repositories\ElasticSearchRepository` Repository](#the-elastic-search-repository)
- [The `Webkul\Product\Repositories\ProductRepository` Repository](#the-product-repository)
- [Renamed Shop API Routes Names](#renamed-shop-api-routes-names)
- [Renamed Shop Controller Method Names](#renamed-shop-controller-method-names)

</div>

<a name="medium-impact-changes"></a>
## Medium Impact Changes

<div class="content-list" markdown="1">
</div>

<a name="low-impact-changes"></a>
## Low Impact Changes

<div class="content-list" markdown="1">
</div>


<a name="upgrade-2.2.0"></a>
## Upgrading To v2.1.0 From v2.2.0

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.



<a name="updating-dependencies"></a>
### Updating Dependencies

**Likelihood Of Impact: High**

#### PHP 8.2.0 Required

Laravel now requires PHP 8.2.0 or greater.

#### curl 7.34.0 Required

Laravel's HTTP client now requires curl 7.34.0 or greater.

#### Composer Dependencies

There is no dependency needed to be updated at for this upgrade.

<div class="content-list" markdown="1">
</div>



<a name="checkout"></a>
### Checkout

<a name="the-cart-model"></a>
#### The `Webkul\Checkout\Models\Cart` model

1. We've removed the `addresses` function as this function is no longer needed.

```diff

- public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
- {
-     return $this->hasMany(CartAddressProxy::modelClass());
- }

```

2. We've updated the `billing_address` function to return HasOne object instead of HasMany and have removed `getBillingAddressAttribute` accessor as `billing_address` will work same as this

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

3. We've updated the `shipping_address` function to return HasOne object instead of HasMany and have removed `getShippingAddressAttribute' accessor as `shipping_address` will work same as this

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

4. We've updated the `shipping_rates` function to return HasMany object instead of HasManyThrough object as shipping rates are now directly related to the cart.

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

The following traits has been removed and all the function has been moved to the `Webkul\Checkout\Cart` class (Reference #9595)

<div class="content-list" markdown="1">

- `Webkul\Checkout\Traits\CartCoupons` trait
- `Webkul\Checkout\Traits\CartTools` trait
- `Webkul\Checkout\Traits\CartValidators` trait

</div>


<a name="the-cart-class"></a>
#### The `Webkul\Checkout\Cart` class

1. The `initCart` function now accept a optional `$customer` model instance and initialize the cart based on this parameter


```diff
- public function initCart()
+ public function initCart(?CustomerContract $customer = null): void

```

2. The `getCart` function now only returns the cart means from now on it will not fetch the current cart

3. We've updated the `addProduct` method to accept first parameter as product model instance instead of product id

```diff

- public function addProduct($productId, $data)
+ public function addProduct(ProductContract $product, array $data): Contracts\Cart|\Exception

```

4. We've renamed the `create` function to `createCart`

```diff

- public function create($data)
+ public function createCart(array $data): ?Contracts\Cart

```

4. We've renamed the `emptyCart` function to `removeCart` and now it accept cart model instance

```diff

- public function emptyCart()
+ public function removeCart(Contracts\Cart $cart): void

```



<a name="shop-blade-updates"></a>
### Shop Blade Updates

<a name="moved-coupon-blade"></a>
#### Moved `coupon.blade.php`

1. `packages/Webkul/Shop/src/Resources/views/checkout/cart/coupon.blade.php` moved to `packages/Webkul/Shop/src/Resources/views/checkout/coupon.blade.php` directory as this file is being including in both checkout and cart page



<a name="product"></a>
### Product

<a name="the-elastic-search-repository"></a>
#### The `Webkul\Product\Repositories\ElasticSearchRepository` Repository

1. We've updated the `search` function to accept two arguments, the first argument is an array containing the search parameters (Eg. category_id, etc) and the second argument is an array containing the options.

```diff

- public function search($categoryId, $options)
+ public function search(array $params, array $options): array

```

2.  We've updated the `getFilters` function to accept an array of parameters because request params will come from the search function itself

```diff

- public function getFilters()
+ public function getFilters(array $params): array

```


<a name="the-product-repository"></a>
#### The `Webkul\Product\Repositories\ProductRepository` Repository

1. We've updated the `getAll` function to accept optional search params

```diff

- public function getAll()
+ public function getAll(array $params = [])

```

2. We've updated the `searchFromDatabase` function to accept optional search params

```diff

- public function searchFromDatabase()
+ public function searchFromDatabase(array $params = [])

```

3. We've updated the `searchFromElastic` function to accept optional search params

```diff

- public function searchFromElastic()
+ public function searchFromElastic(array $params = [])

```



<a name="shop-api"></a>
### Shop API

<a name="renamed-shop-api-routes-names"></a>
#### Renamed Shop API Route Names

Following routes name is renamed for the consistency in `packages/Webkul/Shop/src/Routes/api.php` route file (Reference #9586)


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

Controller action names has renamed for the following routes to match consistency in `packages/Webkul/Shop/src/Routes/customer-routes.php` route file (Reference #9586)

```diff
- Route::get('', 'show')->name('shop.customer.session.index');
+ Route::get('', 'index')->name('shop.customer.session.index');

- Route::post('', 'create')->name('shop.customer.session.create');
+ Route::post('', 'store')->name('shop.customer.session.create');

```