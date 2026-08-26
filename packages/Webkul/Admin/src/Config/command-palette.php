<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Command Palette Actions
    |--------------------------------------------------------------------------
    |
    | Things an operator starts rather than places they go. Pages and settings are
    | discovered from the admin menu and the configuration tree, so only actions are
    | declared here.
    |
    | A package adds its own by merging into the `command_palette.actions` key.
    |
    | Each entry accepts:
    |
    |   title       Translation key for the label shown in the palette.
    |   route       An existing admin route name. The palette creates no routes of its own.
    |   params      Route parameters, if the route takes any.
    |   permission  ACL key the admin must hold. Omit for an action anyone may start.
    |   icon        Icon class shown against the result.
    |   keywords    Extra terms the action answers to.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Command Palette Aliases
    |--------------------------------------------------------------------------
    |
    | Extra terms a menu or configuration key answers to, for the times an operator
    | searches for a word the codebase does not use. Keyed by the menu key or the
    | configuration key, so a package may add its own by merging into this key.
    |
    */

    'aliases' => [
        'settings.currencies' => ['currency', 'money', 'exchange rate'],
        'settings.exchange_rates' => ['currency', 'exchange rate', 'conversion'],
        'settings.taxes' => ['tax', 'vat', 'gst'],
        'settings.taxes.categories' => ['tax', 'vat', 'gst'],
        'settings.taxes.rates' => ['tax', 'vat', 'gst'],
        'settings.locales' => ['language', 'translation', 'locale'],
        'settings.data_transfer' => ['import', 'export', 'csv', 'bulk'],
        'settings.users' => ['staff', 'admin user', 'account'],
        'settings.roles' => ['permission', 'acl', 'access'],
        'catalog.products' => ['sku', 'inventory', 'stock'],
        'catalog.categories' => ['category', 'taxonomy'],
        'catalog.attributes' => ['attribute', 'eav', 'option'],
        'sales.orders' => ['order', 'purchase'],
        'sales.invoices' => ['invoice', 'billing'],
        'sales.refunds' => ['refund', 'credit memo', 'return'],
        'sales.shipments' => ['shipment', 'delivery', 'tracking'],
        'customers.customers' => ['customer', 'buyer', 'shopper'],
        'cms' => ['page', 'content', 'static'],
        'emails.configure' => ['mail', 'smtp', 'sender'],
        'emails.configure.smtp' => ['mail', 'smtp', 'mail server', 'brevo'],
        'sales.carriers' => ['shipping', 'delivery', 'carrier'],
        'sales.payment_methods' => ['payment', 'gateway', 'checkout'],
        'general.gdpr' => ['gdpr', 'privacy', 'consent', 'cookie'],
        'search_engines' => ['search', 'elasticsearch', 'elastic', 'index'],
        'search_engines.elastic' => ['elasticsearch', 'elastic', 'search', 'index'],
        'file_management' => ['storage', 'disk', 's3', 'upload'],
        'cache_management' => ['cache', 'clear cache', 'flush'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Palette Records
    |--------------------------------------------------------------------------
    |
    | Live records the palette searches through the admin's existing search endpoints,
    | rather than holding in its index. Each entry reuses a route that already exists.
    |
    |   key         Identifies the group in the palette.
    |   title       Translation key for the group heading.
    |   permission  ACL key the admin must hold to have the group searched at all.
    |   endpoint    Existing search route, called with a `query` parameter.
    |   link        Route a result opens, given the record id.
    |   label       Record fields joined to form the row's label.
    |   prefix      Put before the label, for the times a record reads better with one.
    |   meta        Record field shown beneath the label.
    |
    */

    'records' => [
        [
            'key' => 'products',
            'title' => 'admin::app.command-palette.records.products',
            'permission' => 'catalog.products',
            'endpoint' => 'admin.catalog.products.search',
            'link' => 'admin.catalog.products.edit',
            'label' => ['name'],
            'meta' => 'sku',
            'icon' => 'icon-product',
        ], [
            'key' => 'orders',
            'title' => 'admin::app.command-palette.records.orders',
            'permission' => 'sales.orders',
            'endpoint' => 'admin.sales.orders.search',
            'link' => 'admin.sales.orders.view',
            'label' => ['increment_id'],
            'prefix' => '#',
            'icon' => 'icon-sales',
        ], [
            'key' => 'categories',
            'title' => 'admin::app.command-palette.records.categories',
            'permission' => 'catalog.categories',
            'endpoint' => 'admin.catalog.categories.search',
            'link' => 'admin.catalog.categories.edit',
            'label' => ['name'],
            'icon' => 'icon-folder',
        ], [
            'key' => 'customers',
            'title' => 'admin::app.command-palette.records.customers',
            'permission' => 'customers.customers',
            'endpoint' => 'admin.customers.customers.search',
            'link' => 'admin.customers.customers.view',
            'label' => ['first_name', 'last_name'],
            'meta' => 'email',
            'icon' => 'icon-customer',
        ],
    ],

    'actions' => [
        [
            'title' => 'admin::app.command-palette.actions.create-product',
            'route' => 'admin.catalog.products.index',
            'permission' => 'catalog.products.create',
            'icon' => 'icon-product',
            'keywords' => ['new', 'add', 'product', 'catalog'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-category',
            'route' => 'admin.catalog.categories.create',
            'permission' => 'catalog.categories.create',
            'icon' => 'icon-folder',
            'keywords' => ['new', 'add', 'category', 'catalog'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-attribute',
            'route' => 'admin.catalog.attributes.create',
            'permission' => 'catalog.attributes.create',
            'icon' => 'icon-attribute',
            'keywords' => ['new', 'add', 'attribute'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-order',
            'route' => 'admin.sales.orders.create',
            'permission' => 'sales.orders.create',
            'icon' => 'icon-sales',
            'keywords' => ['new', 'add', 'order', 'sale'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-customer',
            'route' => 'admin.customers.customers.index',
            'permission' => 'customers.customers.create',
            'icon' => 'icon-customer',
            'keywords' => ['new', 'add', 'customer'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-page',
            'route' => 'admin.cms.create',
            'permission' => 'cms.create',
            'icon' => 'icon-cms',
            'keywords' => ['new', 'add', 'page', 'cms', 'content'],
        ], [
            'title' => 'admin::app.command-palette.actions.create-user',
            'route' => 'admin.settings.users.create',
            'permission' => 'settings.users.create',
            'icon' => 'icon-settings',
            'keywords' => ['new', 'add', 'user', 'admin', 'staff'],
        ], [
            'title' => 'admin::app.command-palette.actions.import-products',
            'route' => 'admin.settings.data_transfer.imports.index',
            'permission' => 'settings.data_transfer.imports',
            'icon' => 'icon-admin-export',
            'keywords' => ['import', 'upload', 'csv', 'xlsx', 'data transfer', 'bulk'],
        ],
    ],
];
