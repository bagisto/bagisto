<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | All ACLs related to dashboard will be placed here.
    |
    */
    [
        'key'   => 'dashboard',
        'name'  => 'admin::app.acl.dashboard',
        'route' => 'admin.dashboard.index',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sales
    |--------------------------------------------------------------------------
    |
    | All ACLs related to sales will be placed here.
    |
    */
    [
        'key'   => 'sales',
        'name'  => 'admin::app.acl.sales',
        'route' => 'admin.sales.orders.index',
        'sort'  => 2,
    ], [
        'key'   => 'sales.orders',
        'name'  => 'admin::app.acl.orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 1,
    ], [
        'key'   => 'sales.orders.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.sales.orders.create',
        'sort'  => 1,
    ], [
        'key'   => 'sales.orders.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.sales.orders.view',
        'sort'  => 2,
    ], [
        'key'   => 'sales.orders.cancel',
        'name'  => 'admin::app.acl.cancel',
        'route' => 'admin.sales.orders.cancel',
        'sort'  => 3,
    ], [
        'key'   => 'sales.invoices',
        'name'  => 'admin::app.acl.invoices',
        'route' => 'admin.sales.invoices.index',
        'sort'  => 2,
    ], [
        'key'   => 'sales.invoices.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.sales.invoices.view',
        'sort'  => 1,
    ], [
        'key'   => 'sales.invoices.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.sales.invoices.store',
        'sort'  => 2,
    ], [
        'key'   => 'sales.shipments',
        'name'  => 'admin::app.acl.shipments',
        'route' => 'admin.sales.shipments.index',
        'sort'  => 3,
    ], [
        'key'   => 'sales.shipments.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.sales.shipments.view',
        'sort'  => 1,
    ], [
        'key'   => 'sales.shipments.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.sales.shipments.store',
        'sort'  => 2,
    ], [
        'key'   => 'sales.refunds',
        'name'  => 'admin::app.acl.refunds',
        'route' => 'admin.sales.refunds.index',
        'sort'  => 4,
    ], [
        'key'   => 'sales.refunds.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.sales.refunds.view',
        'sort'  => 1,
    ], [
        'key'   => 'sales.refunds.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.sales.refunds.store',
        'sort'  => 2,
    ], [
        'key'   => 'sales.transactions',
        'name'  => 'admin::app.acl.transactions',
        'route' => 'admin.sales.transactions.index',
        'sort'  => 5,
    ], [
        'key'   => 'sales.transactions.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.sales.transactions.view',
        'sort'  => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Catalog
    |--------------------------------------------------------------------------
    |
    | All ACLs related to catalog will be placed here.
    |
    */
    [
        'key'   => 'catalog',
        'name'  => 'admin::app.acl.catalog',
        'route' => 'admin.catalog.products.index',
        'sort'  => 3,
    ], [
        'key'   => 'catalog.products',
        'name'  => 'admin::app.acl.products',
        'route' => 'admin.catalog.products.index',
        'sort'  => 1,
    ], [
        'key'   => 'catalog.products.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.catalog.products.store',
        'sort'  => 1,
    ], [
        'key'   => 'catalog.products.copy',
        'name'  => 'admin::app.acl.copy',
        'route' => 'admin.catalog.products.copy',
        'sort'  => 2,
    ], [
        'key'   => 'catalog.products.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.catalog.products.edit',
        'sort'  => 3,
    ], [
        'key'   => 'catalog.products.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.catalog.products.delete',
        'sort'  => 4,
    ], [
        'key'   => 'catalog.categories',
        'name'  => 'admin::app.acl.categories',
        'route' => 'admin.catalog.categories.index',
        'sort'  => 2,
    ], [
        'key'   => 'catalog.categories.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.catalog.categories.create',
        'sort'  => 1,
    ], [
        'key'   => 'catalog.categories.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.catalog.categories.edit',
        'sort'  => 2,
    ], [
        'key'   => 'catalog.categories.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.catalog.categories.delete',
        'sort'  => 3,
    ], [
        'key'   => 'catalog.attributes',
        'name'  => 'admin::app.acl.attributes',
        'route' => 'admin.catalog.attributes.index',
        'sort'  => 3,
    ], [
        'key'   => 'catalog.attributes.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.catalog.attributes.create',
        'sort'  => 1,
    ], [
        'key'   => 'catalog.attributes.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.catalog.attributes.edit',
        'sort'  => 2,
    ], [
        'key'   => 'catalog.attributes.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.catalog.attributes.delete',
        'sort'  => 3,
    ], [
        'key'   => 'catalog.families',
        'name'  => 'admin::app.acl.attribute-families',
        'route' => 'admin.catalog.families.index',
        'sort'  => 4,
    ], [
        'key'   => 'catalog.families.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.catalog.families.create',
        'sort'  => 1,
    ], [
        'key'   => 'catalog.families.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.catalog.families.edit',
        'sort'  => 2,
    ], [
        'key'   => 'catalog.families.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.catalog.families.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Customers
    |--------------------------------------------------------------------------
    |
    | All ACLs related to customers will be placed here.
    |
    */
    [
        'key'   => 'customers',
        'name'  => 'admin::app.acl.customers',
        'route' => 'admin.customers.customers.index',
        'sort'  => 4,
    ], [
        'key'   => 'customers.customers',
        'name'  => 'admin::app.acl.customers',
        'route' => 'admin.customers.customers.index',
        'sort'  => 1,
    ], [
        'key'   => 'customers.customers.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.customer.create',
        'sort'  => 1,
    ], [
        'key'   => 'customers.customers.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.customers.customers.edit',
        'sort'  => 2,
    ], [
        'key'   => 'customers.customers.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.customers.customers.delete',
        'sort'  => 3,
    ], [
        'key'   => 'customers.addresses',
        'name'  => 'admin::app.acl.addresses',
        'route' => 'admin.customers.customers.addresses.index',
        'sort'  => 2,
    ], [
        'key'   => 'customers.addresses.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.customers.customers.addresses.create',
        'sort'  => 1,
    ], [
        'key'   => 'customers.addresses.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.customers.customers.addresses.edit',
        'sort'  => 2,
    ], [
        'key'   => 'customers.addresses.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.customers.customers.addresses.delete',
        'sort'  => 3,
    ], [
        'key'   => 'customers.note',
        'name'  => 'admin::app.acl.note',
        'route' => 'admin.customer.note.create',
        'sort'  => 3,
    ], [
        'key'   => 'customers.groups',
        'name'  => 'admin::app.acl.groups',
        'route' => 'admin.customers.groups.index',
        'sort'  => 4,
    ], [
        'key'   => 'customers.groups.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.groups.create',
        'sort'  => 1,
    ], [
        'key'   => 'customers.groups.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.customers.groups.update',
        'sort'  => 2,
    ], [
        'key'   => 'customers.groups.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.customers.groups.delete',
        'sort'  => 3,
    ], [
        'key'   => 'customers.reviews',
        'name'  => 'admin::app.acl.reviews',
        'route' => 'admin.customers.customers.review.index',
        'sort'  => 5,
    ], [
        'key'   => 'customers.reviews.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.customers.customers.review.edit',
        'sort'  => 1,
    ], [
        'key'   => 'customers.reviews.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.customers.customers.review.delete',
        'sort'  => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketing
    |--------------------------------------------------------------------------
    |
    | All ACLs related to marketing will be placed here.
    |
    */
    [
        'key'   => 'marketing',
        'name'  => 'admin::app.acl.marketing',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort'  => 6,
    ], [
        'key'   => 'marketing.promotions',
        'name'  => 'admin::app.acl.promotions',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.cart_rules',
        'name'  => 'admin::app.acl.cart-rules',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.cart_rules.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.promotions.cart_rules.create',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.cart_rules.copy',
        'name'  => 'admin::app.acl.copy',
        'route' => 'admin.marketing.promotions.cart_rules.copy',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.cart_rules.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.promotions.cart_rules.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.promotions.cart_rules.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.promotions.cart_rules.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.promotions.catalog_rules',
        'name'  => 'admin::app.acl.catalog-rules',
        'route' => 'admin.marketing.promotions.catalog_rules.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.catalog_rules.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.promotions.catalog_rules.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.promotions.catalog_rules.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.promotions.catalog_rules.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.promotions.catalog_rules.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.promotions.catalog_rules.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications',
        'name'  => 'admin::app.acl.communications',
        'route' => 'admin.marketing.communications.email_templates.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.communications.email_templates',
        'name'  => 'admin::app.acl.email-templates',
        'route' => 'admin.marketing.communications.email_templates.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.communications.email_templates.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.communications.email_templates.create',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.communications.email_templates.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.communications.email_templates.edit',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications.email_templates.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.email_templates.delete',
        'sort'  => 4,
    ], [
        'key'   => 'marketing.communications.events',
        'name'  => 'admin::app.acl.events',
        'route' => 'admin.marketing.communications.events.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.communications.events.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.communications.events.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.communications.events.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.communications.events.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.communications.events.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.events.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications.campaigns',
        'name'  => 'admin::app.acl.campaigns',
        'route' => 'admin.marketing.communications.campaigns.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications.campaigns.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.communications.campaigns.create',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.communications.campaigns.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.communications.campaigns.edit',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.communications.campaigns.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.campaigns.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications.subscribers',
        'name'  => 'admin::app.acl.subscribers',
        'route' => 'admin.marketing.communications.subscribers.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.communications.subscribers.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.communications.subscribers.edit',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.communications.subscribers.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.subscribers.delete',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo',
        'name'  => 'admin::app.acl.search-seo',
        'route' => 'admin.marketing.search_seo.url_rewrites.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.search_seo.url_rewrites',
        'name'  => 'admin::app.acl.url-rewrites',
        'route' => 'admin.marketing.search_seo.url_rewrites.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.search_seo.url_rewrites.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.url_rewrites.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.search_seo.url_rewrites.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.url_rewrites.update',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo.url_rewrites.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.search_seo.url_rewrites.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.search_seo.search_terms',
        'name'  => 'admin::app.acl.search-terms',
        'route' => 'admin.marketing.search_seo.search_terms.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo.search_terms.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.search_terms.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.search_seo.search_terms.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.search_terms.update',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo.search_terms.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.search_seo.search_terms.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.search_seo.search_synonyms',
        'name'  => 'admin::app.acl.search-synonyms',
        'route' => 'admin.marketing.search_seo.search_synonyms.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.search_seo.search_synonyms.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.search_synonyms.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.search_seo.search_synonyms.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.search_synonyms.update',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo.search_synonyms.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.search_seo.search_synonyms.delete',
        'sort'  => 3,
    ], [
        'key'   => 'marketing.search_seo.sitemaps',
        'name'  => 'admin::app.acl.sitemaps',
        'route' => 'admin.marketing.search_seo.sitemaps.index',
        'sort'  => 4,
    ], [
        'key'   => 'marketing.search_seo.sitemaps.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.sitemaps.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketing.search_seo.sitemaps.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.sitemaps.update',
        'sort'  => 2,
    ], [
        'key'   => 'marketing.search_seo.sitemaps.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketing.search_seo.sitemaps.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    |
    | All Reporting related to reporting will be placed here.
    |
    */
    [
        'key'   => 'reporting',
        'name'  => 'admin::app.acl.reporting',
        'route' => 'admin.reporting.sales.index',
        'sort'  => 6,
    ], [
        'key'   => 'reporting.sales',
        'name'  => 'admin::app.acl.sales',
        'route' => 'admin.reporting.sales.index',
        'sort'  => 1,
    ], [
        'key'   => 'reporting.customers',
        'name'  => 'admin::app.acl.customers',
        'route' => 'admin.reporting.customers.index',
        'sort'  => 2,
    ], [
        'key'   => 'reporting.products',
        'name'  => 'admin::app.acl.products',
        'route' => 'admin.reporting.products.index',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS
    |--------------------------------------------------------------------------
    |
    | All ACLs related to cms will be placed here.
    |
    */
    [
        'key'   => 'cms',
        'name'  => 'admin::app.acl.cms',
        'route' => 'admin.cms.index',
        'sort'  => 7,
    ], [
        'key'   => 'cms.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.cms.create',
        'sort'  => 1,
    ], [
        'key'   => 'cms.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.cms.edit',
        'sort'  => 2,
    ], [
        'key'   => 'cms.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.cms.delete',
        'sort'  => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | All ACLs related to settings will be placed here.
    |
    */
    [
        'key'   => 'settings',
        'name'  => 'admin::app.acl.settings',
        'route' => 'admin.settings.users.index',
        'sort'  => 8,
    ], [
        'key'   => 'settings.locales',
        'name'  => 'admin::app.acl.locales',
        'route' => 'admin.settings.locales.index',
        'sort'  => 1,
    ], [
        'key'   => 'settings.locales.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.locales.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.locales.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.locales.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.locales.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.locales.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.currencies',
        'name'  => 'admin::app.acl.currencies',
        'route' => 'admin.settings.currencies.index',
        'sort'  => 2,
    ], [
        'key'   => 'settings.currencies.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.currencies.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.currencies.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.currencies.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.currencies.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.currencies.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.exchange_rates',
        'name'  => 'admin::app.acl.exchange-rates',
        'route' => 'admin.settings.exchange_rates.index',
        'sort'  => 3,
    ], [
        'key'   => 'settings.exchange_rates.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.exchange_rates.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.exchange_rates.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.exchange_rates.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.exchange_rates.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.exchange_rates.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.inventory_sources',
        'name'  => 'admin::app.acl.inventory-sources',
        'route' => 'admin.settings.inventory_sources.index',
        'sort'  => 4,
    ], [
        'key'   => 'settings.inventory_sources.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.inventory_sources.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.inventory_sources.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.inventory_sources.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.inventory_sources.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.inventory_sources.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.channels',
        'name'  => 'admin::app.acl.channels',
        'route' => 'admin.settings.channels.index',
        'sort'  => 5,
    ], [
        'key'   => 'settings.channels.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.channels.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.channels.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.channels.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.channels.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.channels.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.users',
        'name'  => 'admin::app.acl.users',
        'route' => 'admin.settings.users.index',
        'sort'  => 6,
    ], [
        'key'   => 'settings.users.users',
        'name'  => 'admin::app.acl.users',
        'route' => 'admin.settings.users.index',
        'sort'  => 1,
    ], [
        'key'   => 'settings.users.users.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.users.store',
        'sort'  => 1,
    ], [
        'key'   => 'settings.users.users.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.users.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.users.users.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.users.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.roles',
        'name'  => 'admin::app.acl.roles',
        'route' => 'admin.settings.roles.index',
        'sort'  => 7,
    ], [
        'key'   => 'settings.roles.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.roles.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.roles.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.roles.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.roles.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.roles.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.themes',
        'name'  => 'admin::app.acl.themes',
        'route' => 'admin.settings.themes.index',
        'sort'  => 8,
    ], [
        'key'   => 'settings.themes.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.themes.store',
        'sort'  => 1,
    ], [
        'key'   => 'settings.themes.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.themes.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.themes.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.themes.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.taxes',
        'name'  => 'admin::app.acl.taxes',
        'route' => 'admin.settings.taxes.categories.index',
        'sort'  => 9,
    ], [
        'key'   => 'settings.taxes.tax_categories',
        'name'  => 'admin::app.acl.tax-categories',
        'route' => 'admin.settings.taxes.categories.index',
        'sort'  => 1,
    ], [
        'key'   => 'settings.taxes.tax_categories.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.taxes.tax_categories.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.taxes.tax_categories.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.taxes.categories.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.taxes.tax_categories.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.taxes.categories.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.taxes.tax_rates',
        'name'  => 'admin::app.acl.tax-rates',
        'route' => 'admin.settings.taxes.rates.index',
        'sort'  => 2,
    ], [
        'key'   => 'settings.taxes.tax_rates.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.taxes.rates.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.taxes.tax_rates.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.taxes.rates.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.data_transfer',
        'name'  => 'admin::app.acl.data-transfer',
        'route' => 'admin.settings.data_transfer.imports.index',
        'sort'  => 10,
    ], [
        'key'   => 'settings.data_transfer.imports',
        'name'  => 'admin::app.acl.imports',
        'route' => 'admin.settings.data_transfer.imports.index',
        'sort'  => 1,
    ], [
        'key'   => 'settings.data_transfer.imports.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.settings.data_transfer.imports.create',
        'sort'  => 1,
    ], [
        'key'   => 'settings.data_transfer.imports.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.settings.data_transfer.imports.edit',
        'sort'  => 2,
    ], [
        'key'   => 'settings.data_transfer.imports.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.settings.data_transfer.imports.delete',
        'sort'  => 3,
    ], [
        'key'   => 'settings.data_transfer.imports.import',
        'name'  => 'admin::app.acl.import',
        'route' => 'admin.settings.data_transfer.imports.import',
        'sort'  => 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | All ACLs related to configuration will be placed here.
    |
    */
    [
        'key'   => 'configuration',
        'name'  => 'admin::app.acl.configure',
        'route' => 'admin.configuration.index',
        'sort'  => 9,
    ],
];
