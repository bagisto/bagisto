# UPGRADE FROM `v2.1.0` TO `v2.2.0`

### Checkout

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