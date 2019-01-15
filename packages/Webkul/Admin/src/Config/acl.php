<?php

return [
    [
        'key' => 'dashboard',
        'name' => 'admin::app.acl.dashboard',
        'route' => 'admin.dashboard.index',
        'sort' => 1
    ], [
        'key' => 'sales',
        'name' => 'admin::app.acl.sales',
        'route' => 'admin.sales.orders.index',
        'sort' => 2
    ], [
        'key' => 'sales.orders',
        'name' => 'admin::app.acl.orders',
        'route' => 'admin.sales.orders.index',
        'sort' => 1
    ], [
        'key' => 'sales.invoices',
        'name' => 'admin::app.acl.invoices',
        'route' => 'admin.sales.invoices.index',
        'sort' => 2
    ], [
        'key' => 'sales.shipments',
        'name' => 'admin::app.acl.shipments',
        'route' => 'admin.sales.shipments.index',
        'sort' => 3
    ], [
        'key' => 'catalog',
        'name' => 'admin::app.acl.catalog',
        'route' => 'admin.catalog.index',
        'sort' => 3
    ], [
        'key' => 'catalog.products',
        'name' => 'admin::app.acl.products',
        'route' => 'admin.catalog.products.index',
        'sort' => 1
    ], [
        'key' => 'catalog.categories',
        'name' => 'admin::app.acl.categories',
        'route' => 'admin.catalog.categories.index',
        'sort' => 2
    ], [
        'key' => 'catalog.attributes',
        'name' => 'admin::app.acl.attributes',
        'route' => 'admin.catalog.attributes.index',
        'sort' => 3
    ], [
        'key' => 'catalog.families',
        'name' => 'admin::app.acl.attribute-families',
        'route' => 'admin.catalog.families.index',
        'sort' => 4
    ], [
        'key' => 'customers',
        'name' => 'admin::app.acl.customers',
        'route' => 'admin.customers.index',
        'sort' => 4
    ], [
        'key' => 'customers.customers',
        'name' => 'admin::app.acl.customers',
        'route' => 'admin.customers.index',
        'sort' => 1
    ], [
        'key' => 'customers.groups',
        'name' => 'admin::app.acl.groups',
        'route' => 'admin.groups.index',
        'sort' => 2
    ], [
        'key' => 'customers.reviews',
        'name' => 'admin::app.acl.reviews',
        'route' => 'admin.customers.reviews.index',
        'sort' => 3
    ], [
        'key' => 'configuration',
        'name' => 'admin::app.acl.configure',
        'route' => 'admin.account.edit',
        'sort' => 5
    ], [
        'key' => 'settings',
        'name' => 'admin::app.acl.settings',
        'route' => 'admin.users.index',
        'sort' => 6
    ], [
        'key' => 'settings.locales',
        'name' => 'admin::app.acl.locales',
        'route' => 'admin.locales.index',
        'sort' => 1
    ], [
        'key' => 'settings.currencies',
        'name' => 'admin::app.acl.currencies',
        'route' => 'admin.currencies.index',
        'sort' => 2
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'admin::app.acl.exchange-rates',
        'route' => 'admin.exchange_rates.index',
        'sort' => 3
    ], [
        'key' => 'settings.inventory_sources',
        'name' => 'admin::app.acl.inventory-sources',
        'route' => 'admin.inventory_sources.index',
        'sort' => 4
    ], [
        'key' => 'settings.channels',
        'name' => 'admin::app.acl.channels',
        'route' => 'admin.channels.index',
        'sort' => 5
    ], [
        'key' => 'settings.users',
        'name' => 'admin::app.acl.users',
        'route' => 'admin.users.index',
        'sort' => 6
    ], [
        'key' => 'settings.users.users',
        'name' => 'admin::app.acl.users',
        'route' => 'admin.users.index',
        'sort' => 1
    ], [
        'key' => 'settings.users.roles',
        'name' => 'admin::app.acl.roles',
        'route' => 'admin.roles.index',
        'sort' => 1
    ], [
        'key' => 'settings.sliders',
        'name' => 'admin::app.acl.sliders',
        'route' => 'admin.sliders.index',
        'sort' => 7
    ], [
        'key' => 'settings.taxes',
        'name' => 'admin::app.acl.taxes',
        'route' => 'admin.tax-categories.index',
        'sort' => 8
    ], [
        'key' => 'settings.taxes.tax-categories',
        'name' => 'admin::app.acl.tax-categories',
        'route' => 'admin.tax-categories.index',
        'sort' => 1
    ], [
        'key' => 'settings.taxes.tax-rates',
        'name' => 'admin::app.acl.tax-rates',
        'route' => 'admin.tax-rates.index',
        'sort' => 2
    ]
];

?>