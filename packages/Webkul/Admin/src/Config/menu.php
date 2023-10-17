<?php

return [
    /**
     * Dashboard.
     */
    [
        'key'        => 'dashboard',
        'name'       => 'admin::app.components.layouts.sidebar.dashboard',
        'route'      => 'admin.dashboard.index',
        'sort'       => 1,
        'icon'       => 'icon-dashboard',
    ],

    /**
     * Sales.
     */
    [
        'key'        => 'sales',
        'name'       => 'admin::app.components.layouts.sidebar.sales',
        'route'      => 'admin.sales.orders.index',
        'sort'       => 2,
        'icon'       => 'icon-sales',
    ], [
        'key'        => 'sales.orders',
        'name'       => 'admin::app.components.layouts.sidebar.orders',
        'route'      => 'admin.sales.orders.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'sales.shipments',
        'name'       => 'admin::app.components.layouts.sidebar.shipments',
        'route'      => 'admin.sales.shipments.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'sales.invoices',
        'name'       => 'admin::app.components.layouts.sidebar.invoices',
        'route'      => 'admin.sales.invoices.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'sales.refunds',
        'name'       => 'admin::app.components.layouts.sidebar.refunds',
        'route'      => 'admin.sales.refunds.index',
        'sort'       => 4,
        'icon'       => '',
    ], [
        'key'        => 'sales.transactions',
        'name'       => 'admin::app.components.layouts.sidebar.transactions',
        'route'      => 'admin.sales.transactions.index',
        'sort'       => 5,
        'icon'       => '',
    ],
    
    /**
     * Catalog.
     */
    [
        'key'        => 'catalog',
        'name'       => 'admin::app.components.layouts.sidebar.catalog',
        'route'      => 'admin.catalog.products.index',
        'sort'       => 3,
        'icon'       => 'icon-product',
    ], [
        'key'        => 'catalog.products',
        'name'       => 'admin::app.components.layouts.sidebar.products',
        'route'      => 'admin.catalog.products.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'catalog.categories',
        'name'       => 'admin::app.components.layouts.sidebar.categories',
        'route'      => 'admin.catalog.categories.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'catalog.attributes',
        'name'       => 'admin::app.components.layouts.sidebar.attributes',
        'route'      => 'admin.catalog.attributes.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'catalog.families',
        'name'       => 'admin::app.components.layouts.sidebar.attribute-families',
        'route'      => 'admin.catalog.families.index',
        'sort'       => 4,
        'icon'       => '',
    ],

    /**
     * Customers.
     */
    [
        'key'        => 'customers',
        'name'       => 'admin::app.components.layouts.sidebar.customers',
        'route'      => 'admin.customers.customers.index',
        'sort'       => 4,
        'icon'       => 'icon-customer-2',
    ], [
        'key'        => 'customers.customers',
        'name'       => 'admin::app.components.layouts.sidebar.customers',
        'route'      => 'admin.customers.customers.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'customers.groups',
        'name'       => 'admin::app.components.layouts.sidebar.groups',
        'route'      => 'admin.customers.groups.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'customers.reviews',
        'name'       => 'admin::app.components.layouts.sidebar.reviews',
        'route'      => 'admin.customers.customers.review.index',
        'sort'       => 3,
        'icon'       => '',
    ],
    
    /**
     * CMS.
     */
    [
        'key'        => 'cms',
        'name'       => 'admin::app.components.layouts.sidebar.cms',
        'route'      => 'admin.cms.index',
        'sort'       => 5,
        'icon'       => 'icon-cms',
    ],
    

    /**
     * Marketing.
     */
    [
        'key'        => 'marketing',
        'name'       => 'admin::app.components.layouts.sidebar.marketing',
        'route'      => 'admin.marketing.promotions.catalog_rules.index',
        'sort'       => 6,
        'icon'       => 'icon-promotion',
        'icon-class' => 'promotion-icon',
    ], [
        'key'        => 'marketing.promotions',
        'name'       => 'admin::app.components.layouts.sidebar.promotions',
        'route'      => 'admin.marketing.promotions.catalog_rules.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.promotions.catalog-rules',
        'name'       => 'admin::app.marketing.promotions.index.catalog-rule-title',
        'route'      => 'admin.marketing.promotions.catalog_rules.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.promotions.cart-rules',
        'name'       => 'admin::app.marketing.promotions.index.cart-rule-title',
        'route'      => 'admin.marketing.promotions.cart_rules.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.communications',
        'name'       => 'admin::app.components.layouts.sidebar.communications',
        'route'      => 'admin.marketing.communications.email_templates.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.communications.email-templates',
        'name'       => 'admin::app.components.layouts.sidebar.email-templates',
        'route'      => 'admin.marketing.communications.email_templates.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'marketing.communications.events',
        'name'       => 'admin::app.components.layouts.sidebar.events',
        'route'      => 'admin.marketing.communications.events.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.communications.campaigns',
        'name'       => 'admin::app.components.layouts.sidebar.campaigns',
        'route'      => 'admin.marketing.communications.campaigns.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'marketing.communications.subscribers',
        'name'       => 'admin::app.components.layouts.sidebar.newsletter-subscriptions',
        'route'      => 'admin.marketing.communications.subscribers.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'marketing.sitemaps',
        'name'       => 'admin::app.components.layouts.sidebar.sitemaps',
        'route'      => 'admin.marketing.promotions.sitemaps.index',
        'sort'       => 3,
        'icon'       => '',
    ],
    
    /**
     * Reporting.
     */
    [
        'key'        => 'reporting',
        'name'       => 'admin::app.components.layouts.sidebar.reporting',
        'route'      => 'admin.reporting.sales.index',
        'sort'       => 7,
        'icon'       => 'icon-report',
        'icon-class' => 'report-icon',
    ], [
        'key'        => 'reporting.sales',
        'name'       => 'admin::app.components.layouts.sidebar.sales',
        'route'      => 'admin.reporting.sales.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'reporting.customers',
        'name'       => 'admin::app.components.layouts.sidebar.customers',
        'route'      => 'admin.reporting.customers.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'reporting.products',
        'name'       => 'admin::app.components.layouts.sidebar.products',
        'route'      => 'admin.reporting.products.index',
        'sort'       => 3,
        'icon'       => '',
    ],

    /**
     * Settings.
     */
    [
        'key'        => 'settings',
        'name'       => 'admin::app.components.layouts.sidebar.settings',
        'route'      => 'admin.settings.locales.index',
        'sort'       => 8,
        'icon'       => 'icon-settings',
        'icon-class' => 'settings-icon',
    ], [
        'key'        => 'settings.locales',
        'name'       => 'admin::app.components.layouts.sidebar.locales',
        'route'      => 'admin.settings.locales.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'settings.currencies',
        'name'       => 'admin::app.components.layouts.sidebar.currencies',
        'route'      => 'admin.settings.currencies.index',
        'sort'       => 2,
        'icon'       => '',
    ], [
        'key'        => 'settings.exchange_rates',
        'name'       => 'admin::app.components.layouts.sidebar.exchange-rates',
        'route'      => 'admin.settings.exchange_rates.index',
        'sort'       => 3,
        'icon'       => '',
    ], [
        'key'        => 'settings.inventory_sources',
        'name'       => 'admin::app.components.layouts.sidebar.inventory-sources',
        'route'      => 'admin.settings.inventory_sources.index',
        'sort'       => 4,
        'icon'       => '',
    ], [
        'key'        => 'settings.channels',
        'name'       => 'admin::app.components.layouts.sidebar.channels',
        'route'      => 'admin.settings.channels.index',
        'sort'       => 5,
        'icon'       => '',
    ], [
        'key'        => 'settings.users',
        'name'       => 'admin::app.components.layouts.sidebar.users',
        'route'      => 'admin.settings.users.index',
        'sort'       => 6,
        'icon'       => '',
    ], [
        'key'        => 'settings.roles',
        'name'       => 'admin::app.components.layouts.sidebar.roles',
        'route'      => 'admin.settings.roles.index',
        'sort'       => 7,
        'icon'       => '',
    ], [
        'key'        => 'settings.themes',
        'name'       => 'Themes',
        'route'      => 'admin.settings.themes.index',
        'sort'       => 8,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes',
        'name'       => 'admin::app.components.layouts.sidebar.taxes',
        'route'      => 'admin.settings.taxes.categories.index',
        'sort'       => 9,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes.tax-categories',
        'name'       => 'admin::app.components.layouts.sidebar.tax-categories',
        'route'      => 'admin.settings.taxes.categories.index',
        'sort'       => 1,
        'icon'       => '',
    ], [
        'key'        => 'settings.taxes.tax-rates',
        'name'       => 'admin::app.components.layouts.sidebar.tax-rates',
        'route'      => 'admin.settings.taxes.rates.index',
        'sort'       => 2,
        'icon'       => '',
    ],

    /**
     * Configuration.
     */
    [
        'key'        => 'configuration',
        'name'       => 'admin::app.components.layouts.sidebar.configure',
        'route'      => 'admin.configuration.index',
        'sort'       => 9,
        'icon'       => 'icon-configuration',
    ]
];
