<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Profile routes.
 */
Breadcrumbs::for('customer.profile.index', function (BreadcrumbTrail $trail) {
    $trail->push(trans('shop::app.customer.account.profile.index.title'), route('customer.profile.index'));
});

Breadcrumbs::for('customer.profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('velocity::app.shop.general.update'), route('customer.profile.edit'));
});

/**
 * Order routes.
 */
Breadcrumbs::for('customer.orders.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.order.index.page-title'), route('customer.orders.index'));
});

Breadcrumbs::for('customer.orders.view', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('customer.orders.index');

    $trail->push(trans('velocity::app.shop.general.view'), route('customer.orders.view', $id));
});

/**
 * Downloadable products.
 */
Breadcrumbs::for('customer.downloadable_products.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.downloadable_products.title'), route('customer.downloadable_products.index'));
});

/**
 * Wishlists.
 */
Breadcrumbs::for('customer.wishlist.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.wishlist.page-title'), route('customer.wishlist.index'));
});

/**
 * Compare.
 */
Breadcrumbs::for('velocity.customer.product.compare', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('velocity::app.customer.compare.compare_similar_items'), route('velocity.customer.product.compare'));
});

/**
 * Reviews.
 */
Breadcrumbs::for('customer.reviews.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.review.index.page-title'), route('customer.reviews.index'));
});

/**
 * Addresses.
 */
Breadcrumbs::for('customer.address.index', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.profile.index');

    $trail->push(trans('shop::app.customer.account.address.index.page-title'), route('customer.address.index'));
});

Breadcrumbs::for('customer.address.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.address.index');

    $trail->push(trans('shop::app.customer.account.address.create.page-title'), route('customer.address.create'));
});

Breadcrumbs::for('customer.address.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('customer.address.index');

    $trail->push(trans('shop::app.customer.account.address.edit.page-title'), route('customer.address.edit', $id));
});