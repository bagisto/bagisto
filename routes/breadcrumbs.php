<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(trans('shop::app.customers.account.home'), route('shop.home.index'));
});

// Home > My Account
Breadcrumbs::for('account', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans('shop::app.layouts.my-account'), route('shop.customers.account.profile.index'));
});

// Home > My Account > Profile
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.profile'), route('shop.customers.account.profile.index'));
});

// Home > My Account > Profile > Edit
Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('profile');
    $trail->push(trans('shop::app.customers.account.profile.edit.edit'), route('shop.customers.account.profile.index'));
});

// Home > My Account > Address
Breadcrumbs::for('addresses', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.address'), route('shop.customers.account.addresses.index'));
});

// Home > My Account > Address > Create
Breadcrumbs::for('addresses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('addresses');
    $trail->push(trans('shop::app.customers.account.addresses.index.add-address'), route('shop.customers.account.addresses.create'));
});

// Home > My Account > Address > Edit
Breadcrumbs::for('addresses.edit', function (BreadcrumbTrail $trail, $entity) {
    $trail->parent('addresses');
    $trail->push(trans('shop::app.customers.account.addresses.index.edit'), route('shop.customers.account.addresses.edit', $entity->id));
});

// Home > My Account > Orders
Breadcrumbs::for('orders', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.orders'), route('shop.customers.account.orders.index'));
});

Breadcrumbs::for('orders.view', function (BreadcrumbTrail $trail, $entity) {
    $trail->parent('orders');
    $trail->push(trans('shop::app.customers.account.orders.view.title'), route('shop.customers.account.orders.view', $entity->id));
});

// Home > My Account > Downloadable Products
Breadcrumbs::for('downloadable-products', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.downloadable-products'), route('shop.customers.account.downloadable_products.index'));
});

// Home > My Account > Reviews
Breadcrumbs::for('reviews', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.reviews'), route('shop.customers.account.reviews.index'));
});

// Home > My Account > Wishlist
Breadcrumbs::for('wishlist', function (BreadcrumbTrail $trail) {
    $trail->parent('account');
    $trail->push(trans('shop::app.layouts.wishlist'), route('shop.customers.account.wishlist.index'));
});

// Home > Cart
Breadcrumbs::for('cart', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans('shop::app.checkout.cart.index.cart'), route('shop.checkout.cart.index'));
});

// Home > Checkout
Breadcrumbs::for('checkout', function (BreadcrumbTrail $trail) {
    $trail->parent('cart');
    $trail->push(trans('shop::app.checkout.onepage.index.checkout'), route('shop.checkout.onepage.index'));
});

// Home > Comapre
Breadcrumbs::for('compare', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans('shop::app.compare.product-compare'), route('shop.compare.index'));
});

// Home > Product
Breadcrumbs::for('product', function (BreadcrumbTrail $trail, $entity) {
    $trail->parent('home');
    $trail->push($entity->name ?? '', route('shop.product_or_category.index', $entity->url_key));
});
