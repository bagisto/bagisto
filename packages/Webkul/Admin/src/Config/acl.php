<?php

return [
    [
        'key' => 'dashboard',
        'name' => 'Dashboard',
        'route' => 'admin.dashboard.index',
        'sort' => 1
    ], [
        'key' => 'sales',
        'name' => 'Sales',
        'route' => 'admin.sales.orders.index',
        'sort' => 2
    ], [
        'key' => 'sales.orders',
        'name' => 'Orders',
        'route' => 'admin.sales.orders.index',
        'sort' => 1
    ], [
        'key' => 'sales.invoices',
        'name' => 'Invoices',
        'route' => 'admin.sales.invoices.index',
        'sort' => 2
    ], [
        'key' => 'sales.shipments',
        'name' => 'Shipments',
        'route' => 'admin.sales.shipments.index',
        'sort' => 3
    ], [
        'key' => 'catalog',
        'name' => 'Catalog',
        'route' => 'admin.catalog.index',
        'sort' => 3
    ], [
        'key' => 'catalog.products',
        'name' => 'Products',
        'route' => 'admin.catalog.products.index',
        'sort' => 1
    ], [
        'key' => 'catalog.categories',
        'name' => 'Categories',
        'route' => 'admin.catalog.categories.index',
        'sort' => 2
    ], [
        'key' => 'catalog.attributes',
        'name' => 'Attributes',
        'route' => 'admin.catalog.attributes.index',
        'sort' => 3
    ], [
        'key' => 'catalog.families',
        'name' => 'Families',
        'route' => 'admin.catalog.families.index',
        'sort' => 4
    ], [
        'key' => 'customers',
        'name' => 'Customers',
        'route' => 'admin.customers.index',
        'sort' => 4
    ], [
        'key' => 'customers.customers',
        'name' => 'Customers',
        'route' => 'admin.customers.index',
        'sort' => 1
    ], [
        'key' => 'customers.groups',
        'name' => 'Groups',
        'route' => 'admin.groups.index',
        'sort' => 2
    ], [
        'key' => 'customers.reviews',
        'name' => 'Reviews',
        'route' => 'admin.customers.reviews.index',
        'sort' => 3
    ], [
        'key' => 'configuration',
        'name' => 'Configure',
        'route' => 'admin.account.edit',
        'sort' => 1
    ], [
        'key' => 'configuration',
        'name' => 'Configure',
        'route' => 'admin.account.edit',
        'sort' => 1
    ], [
        'key' => 'settings',
        'name' => 'Settings',
        'route' => 'admin.users.index',
        'sort' => 6
    ], [
        'key' => 'settings.locales',
        'name' => 'Locales',
        'route' => 'admin.locales.index',
        'sort' => 1
    ], [
        'key' => 'settings.currencies',
        'name' => 'Currencies',
        'route' => 'admin.currencies.index',
        'sort' => 2
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'Exchange Rates',
        'route' => 'admin.exchange_rates.index',
        'sort' => 3
    ], [
        'key' => 'settings.inventory_sources',
        'name' => 'Inventory Sources',
        'route' => 'admin.inventory_sources.index',
        'sort' => 4
    ], [
        'key' => 'settings.channels',
        'name' => 'Channels',
        'route' => 'admin.channels.index',
        'sort' => 5
    ], [
        'key' => 'settings.users',
        'name' => 'Users',
        'route' => 'admin.users.index',
        'sort' => 6
    ], [
        'key' => 'settings.users.users',
        'name' => 'Users',
        'route' => 'admin.users.index',
        'sort' => 1
    ], [
        'key' => 'settings.users.roles',
        'name' => 'Roles',
        'route' => 'admin.roles.index',
        'sort' => 1
    ], [
        'key' => 'settings.sliders',
        'name' => 'Sliders',
        'route' => 'admin.sliders.index',
        'sort' => 7
    ], [
        'key' => 'settings.taxes',
        'name' => 'Taxes',
        'route' => 'admin.tax-categories.index',
        'sort' => 8
    ], [
        'key' => 'settings.taxes.tax-categories',
        'name' => 'Tax Categories',
        'route' => 'admin.tax-categories.index',
        'sort' => 1
    ], [
        'key' => 'settings.taxes.tax-rates',
        'name' => 'Tax Rates',
        'route' => 'admin.tax-rates.index',
        'sort' => 2
    ]
];

?>