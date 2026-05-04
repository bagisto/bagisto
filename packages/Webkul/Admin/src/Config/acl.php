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
        'key' => 'dashboard',
        'name' => 'admin::app.acl.dashboard',
        'route' => 'admin.dashboard.index',
        'sort' => 1,
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
        'key' => 'sales',
        'name' => 'admin::app.acl.sales',
        'route' => 'admin.sales.orders.index',
        'sort' => 2,
    ], [
        'key' => 'sales.orders',
        'name' => 'admin::app.acl.orders',
        'route' => 'admin.sales.orders.index',
        'sort' => 1,
    ], [
        'key' => 'sales.orders.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.sales.orders.create',
            'admin.sales.orders.store',
            'admin.sales.cart.store',
            'admin.sales.cart.items.store',
            'admin.sales.cart.items.update',
            'admin.sales.cart.items.destroy',
            'admin.sales.cart.addresses.store',
            'admin.sales.cart.shipping_methods.store',
            'admin.sales.cart.payment_methods.store',
            'admin.customers.customers.cart.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'sales.orders.view',
        'name' => 'admin::app.acl.view',
        'route' => 'admin.sales.orders.view',
        'sort' => 2,
    ], [
        'key' => 'sales.orders.cancel',
        'name' => 'admin::app.acl.cancel',
        'route' => 'admin.sales.orders.cancel',
        'sort' => 3,
    ], [
        'key' => 'sales.invoices',
        'name' => 'admin::app.acl.invoices',
        'route' => 'admin.sales.invoices.index',
        'sort' => 2,
    ], [
        'key' => 'sales.invoices.view',
        'name' => 'admin::app.acl.view',
        'route' => 'admin.sales.invoices.view',
        'sort' => 1,
    ], [
        'key' => 'sales.invoices.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.sales.invoices.store',
        'sort' => 2,
    ], [
        'key' => 'sales.shipments',
        'name' => 'admin::app.acl.shipments',
        'route' => 'admin.sales.shipments.index',
        'sort' => 3,
    ], [
        'key' => 'sales.shipments.view',
        'name' => 'admin::app.acl.view',
        'route' => 'admin.sales.shipments.view',
        'sort' => 1,
    ], [
        'key' => 'sales.shipments.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.sales.shipments.store',
        'sort' => 2,
    ], [
        'key' => 'sales.refunds',
        'name' => 'admin::app.acl.refunds',
        'route' => 'admin.sales.refunds.index',
        'sort' => 4,
    ], [
        'key' => 'sales.refunds.view',
        'name' => 'admin::app.acl.view',
        'route' => 'admin.sales.refunds.view',
        'sort' => 1,
    ], [
        'key' => 'sales.refunds.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.sales.refunds.store',
            'admin.sales.refunds.update_totals',
        ],
        'sort' => 2,
    ], [
        'key' => 'sales.transactions',
        'name' => 'admin::app.acl.transactions',
        'route' => [
            'admin.sales.transactions.index',
            'admin.sales.transactions.store',
        ],
        'sort' => 5,
    ], [
        'key' => 'sales.transactions.view',
        'name' => 'admin::app.acl.view',
        'route' => 'admin.sales.transactions.view',
        'sort' => 1,
    ], [
        'key' => 'sales.rma',
        'name' => 'admin::app.acl.rma.title',
        'route' => 'admin.sales.rma.requests.index',
        'sort' => 6,
    ], [
        'key' => 'sales.rma.requests',
        'name' => 'admin::app.acl.rma.requests.title',
        'route' => 'admin.sales.rma.requests.index',
        'sort' => 1,
    ], [
        'key' => 'sales.rma.requests.create',
        'name' => 'admin::app.acl.rma.requests.create',
        'route' => [
            'admin.sales.rma.requests.create',
            'admin.sales.rma.requests.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'sales.rma.reasons',
        'name' => 'admin::app.acl.rma.reasons.title',
        'route' => 'admin.sales.rma.reasons.index',
        'sort' => 2,
    ], [
        'key' => 'sales.rma.reasons.create',
        'name' => 'admin::app.acl.rma.reasons.create',
        'route' => 'admin.sales.rma.reasons.store',
        'sort' => 1,
    ], [
        'key' => 'sales.rma.reasons.edit',
        'name' => 'admin::app.acl.rma.reasons.edit',
        'route' => [
            'admin.sales.rma.reasons.edit',
            'admin.sales.rma.reasons.update',
            'admin.sales.rma.reasons.mass-update',
        ],
        'sort' => 2,
    ], [
        'key' => 'sales.rma.reasons.delete',
        'name' => 'admin::app.acl.rma.reasons.delete',
        'route' => [
            'admin.sales.rma.reasons.delete',
            'admin.sales.rma.reasons.mass-delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'sales.rma.rules',
        'name' => 'admin::app.acl.rma.rules.title',
        'route' => 'admin.sales.rma.rules.index',
        'sort' => 3,
    ], [
        'key' => 'sales.rma.rules.create',
        'name' => 'admin::app.acl.rma.rules.create',
        'route' => 'admin.sales.rma.rules.store',
        'sort' => 1,
    ], [
        'key' => 'sales.rma.rules.edit',
        'name' => 'admin::app.acl.rma.rules.edit',
        'route' => [
            'admin.sales.rma.rules.edit',
            'admin.sales.rma.rules.update',
            'admin.sales.rma.rules.mass-update',
        ],
        'sort' => 2,
    ], [
        'key' => 'sales.rma.rules.delete',
        'name' => 'admin::app.acl.rma.rules.delete',
        'route' => [
            'admin.sales.rma.rules.delete',
            'admin.sales.rma.rules.mass-delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'sales.rma.statuses',
        'name' => 'admin::app.acl.rma.statuses.title',
        'route' => 'admin.sales.rma.statuses.index',
        'sort' => 4,
    ], [
        'key' => 'sales.rma.statuses.create',
        'name' => 'admin::app.acl.rma.statuses.create',
        'route' => 'admin.sales.rma.statuses.store',
        'sort' => 1,
    ], [
        'key' => 'sales.rma.statuses.edit',
        'name' => 'admin::app.acl.rma.statuses.edit',
        'route' => [
            'admin.sales.rma.statuses.edit',
            'admin.sales.rma.statuses.update',
            'admin.sales.rma.statuses.mass-update',
        ],
        'sort' => 2,
    ], [
        'key' => 'sales.rma.statuses.delete',
        'name' => 'admin::app.acl.rma.statuses.delete',
        'route' => [
            'admin.sales.rma.statuses.delete',
            'admin.sales.rma.statuses.mass-delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'sales.rma.custom-fields',
        'name' => 'admin::app.acl.rma.custom-fields.title',
        'route' => 'admin.sales.rma.custom-fields.index',
        'sort' => 5,
    ], [
        'key' => 'sales.rma.custom-fields.create',
        'name' => 'admin::app.acl.rma.custom-fields.create',
        'route' => [
            'admin.sales.rma.custom-fields.create',
            'admin.sales.rma.custom-fields.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'sales.rma.custom-fields.edit',
        'name' => 'admin::app.acl.rma.custom-fields.edit',
        'route' => [
            'admin.sales.rma.custom-fields.edit',
            'admin.sales.rma.custom-fields.update',
            'admin.sales.rma.custom-fields.mass-update',
        ],
        'sort' => 2,
    ], [
        'key' => 'sales.rma.custom-fields.delete',
        'name' => 'admin::app.acl.rma.custom-fields.delete',
        'route' => [
            'admin.sales.rma.custom-fields.delete',
            'admin.sales.rma.custom-fields.mass-delete',
        ],
        'sort' => 3,
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
        'key' => 'catalog',
        'name' => 'admin::app.acl.catalog',
        'route' => 'admin.catalog.products.index',
        'sort' => 3,
    ], [
        'key' => 'catalog.products',
        'name' => 'admin::app.acl.products',
        'route' => 'admin.catalog.products.index',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.catalog.products.store',
        'sort' => 1,
    ], [
        'key' => 'catalog.products.copy',
        'name' => 'admin::app.acl.copy',
        'route' => 'admin.catalog.products.copy',
        'sort' => 2,
    ], [
        'key' => 'catalog.products.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.catalog.products.edit',
            'admin.catalog.products.update',
            'admin.catalog.products.update_inventories',
            'admin.catalog.products.mass_update',
        ],
        'sort' => 3,
    ], [
        'key' => 'catalog.products.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.catalog.products.delete',
            'admin.catalog.products.mass_delete',
        ],
        'sort' => 4,
    ], [
        'key' => 'catalog.categories',
        'name' => 'admin::app.acl.categories',
        'route' => 'admin.catalog.categories.index',
        'sort' => 2,
    ], [
        'key' => 'catalog.categories.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.catalog.categories.create',
            'admin.catalog.categories.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'catalog.categories.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.catalog.categories.edit',
            'admin.catalog.categories.update',
            'admin.catalog.categories.mass_update',
        ],
        'sort' => 2,
    ], [
        'key' => 'catalog.categories.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.catalog.categories.delete',
            'admin.catalog.categories.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'catalog.attributes',
        'name' => 'admin::app.acl.attributes',
        'route' => 'admin.catalog.attributes.index',
        'sort' => 3,
    ], [
        'key' => 'catalog.attributes.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.catalog.attributes.create',
            'admin.catalog.attributes.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'catalog.attributes.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.catalog.attributes.edit',
            'admin.catalog.attributes.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'catalog.attributes.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.catalog.attributes.delete',
            'admin.catalog.attributes.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'catalog.families',
        'name' => 'admin::app.acl.attribute-families',
        'route' => 'admin.catalog.families.index',
        'sort' => 4,
    ], [
        'key' => 'catalog.families.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.catalog.families.create',
            'admin.catalog.families.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'catalog.families.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.catalog.families.edit',
            'admin.catalog.families.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'catalog.families.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.catalog.families.delete',
        'sort' => 3,
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
        'key' => 'customers',
        'name' => 'admin::app.acl.customers',
        'route' => 'admin.customers.customers.index',
        'sort' => 4,
    ], [
        'key' => 'customers.customers',
        'name' => 'admin::app.acl.customers',
        'route' => 'admin.customers.customers.index',
        'sort' => 1,
    ], [
        'key' => 'customers.customers.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.customers.customers.store',
        'sort' => 1,
    ], [
        'key' => 'customers.customers.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.customers.customers.update',
            'admin.customers.customers.mass_update',
        ],
        'sort' => 2,
    ], [
        'key' => 'customers.customers.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.customers.customers.delete',
            'admin.customers.customers.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'customers.addresses',
        'name' => 'admin::app.acl.addresses',
        'route' => 'admin.customers.customers.addresses.index',
        'sort' => 2,
    ], [
        'key' => 'customers.addresses.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.customers.customers.addresses.create',
            'admin.customers.customers.addresses.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'customers.addresses.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.customers.customers.addresses.edit',
            'admin.customers.customers.addresses.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'customers.addresses.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.customers.customers.addresses.delete',
        'sort' => 3,
    ], [
        'key' => 'customers.note',
        'name' => 'admin::app.acl.note',
        'route' => 'admin.customer.note.store',
        'sort' => 3,
    ], [
        'key' => 'customers.groups',
        'name' => 'admin::app.acl.groups',
        'route' => 'admin.customers.groups.index',
        'sort' => 4,
    ], [
        'key' => 'customers.groups.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.customers.groups.store',
        'sort' => 1,
    ], [
        'key' => 'customers.groups.edit',
        'name' => 'admin::app.acl.edit',
        'route' => 'admin.customers.groups.update',
        'sort' => 2,
    ], [
        'key' => 'customers.groups.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.customers.groups.delete',
        'sort' => 3,
    ], [
        'key' => 'customers.reviews',
        'name' => 'admin::app.acl.reviews',
        'route' => 'admin.customers.customers.review.index',
        'sort' => 5,
    ], [
        'key' => 'customers.reviews.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.customers.customers.review.edit',
            'admin.customers.customers.review.update',
            'admin.customers.customers.review.mass_update',
        ],
        'sort' => 1,
    ], [
        'key' => 'customers.reviews.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.customers.customers.review.delete',
            'admin.customers.customers.review.mass_delete',
        ],
        'sort' => 2,
    ], [
        'key' => 'customers.gdpr_requests',
        'name' => 'admin::app.acl.gdpr',
        'route' => 'admin.customers.gdpr.index',
        'sort' => 6,
    ], [
        'key' => 'customers.gdpr_requests.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.customers.gdpr.edit',
            'admin.customers.gdpr.update',
        ],
        'sort' => 1,
    ], [
        'key' => 'customers.gdpr_requests.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.customers.gdpr.delete',
        'sort' => 2,
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
        'key' => 'marketing',
        'name' => 'admin::app.acl.marketing',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort' => 6,
    ], [
        'key' => 'marketing.promotions',
        'name' => 'admin::app.acl.promotions',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules',
        'name' => 'admin::app.acl.cart-rules',
        'route' => 'admin.marketing.promotions.cart_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.marketing.promotions.cart_rules.create',
            'admin.marketing.promotions.cart_rules.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.copy',
        'name' => 'admin::app.acl.copy',
        'route' => 'admin.marketing.promotions.cart_rules.copy',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.cart_rules.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.promotions.cart_rules.edit',
            'admin.marketing.promotions.cart_rules.update',
            'admin.marketing.promotions.cart_rules.coupons.store',
            'admin.marketing.promotions.cart_rules.coupons.mass_delete',
        ],
        'sort' => 2,
    ], [
        'key' => 'marketing.promotions.cart_rules.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.promotions.cart_rules.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.promotions.catalog_rules',
        'name' => 'admin::app.acl.catalog-rules',
        'route' => 'admin.marketing.promotions.catalog_rules.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.catalog_rules.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.marketing.promotions.catalog_rules.create',
            'admin.marketing.promotions.catalog_rules.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'marketing.promotions.catalog_rules.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.promotions.catalog_rules.edit',
            'admin.marketing.promotions.catalog_rules.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'marketing.promotions.catalog_rules.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.promotions.catalog_rules.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications',
        'name' => 'admin::app.acl.communications',
        'route' => 'admin.marketing.communications.email_templates.index',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.email_templates',
        'name' => 'admin::app.acl.email-templates',
        'route' => 'admin.marketing.communications.email_templates.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.email_templates.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.marketing.communications.email_templates.create',
            'admin.marketing.communications.email_templates.store',
        ],
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.email_templates.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.communications.email_templates.edit',
            'admin.marketing.communications.email_templates.update',
        ],
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.email_templates.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.email_templates.delete',
        'sort' => 4,
    ], [
        'key' => 'marketing.communications.events',
        'name' => 'admin::app.acl.events',
        'route' => 'admin.marketing.communications.events.index',
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.events.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.marketing.communications.events.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.events.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.communications.events.edit',
            'admin.marketing.communications.events.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.events.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.events.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.campaigns',
        'name' => 'admin::app.acl.campaigns',
        'route' => 'admin.marketing.communications.campaigns.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.campaigns.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.marketing.communications.campaigns.create',
            'admin.marketing.communications.campaigns.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.campaigns.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.communications.campaigns.edit',
            'admin.marketing.communications.campaigns.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'marketing.communications.campaigns.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.campaigns.delete',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.subscribers',
        'name' => 'admin::app.acl.subscribers',
        'route' => 'admin.marketing.communications.subscribers.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.communications.subscribers.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.marketing.communications.subscribers.edit',
            'admin.marketing.communications.subscribers.update',
        ],
        'sort' => 1,
    ], [
        'key' => 'marketing.communications.subscribers.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.communications.subscribers.delete',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo',
        'name' => 'admin::app.acl.search-seo',
        'route' => 'admin.marketing.search_seo.url_rewrites.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.url_rewrites',
        'name' => 'admin::app.acl.url-rewrites',
        'route' => 'admin.marketing.search_seo.url_rewrites.index',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.url_rewrites.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.edit',
        'name' => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.url_rewrites.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.url_rewrites.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.marketing.search_seo.url_rewrites.delete',
            'admin.marketing.search_seo.url_rewrites.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_terms',
        'name' => 'admin::app.acl.search-terms',
        'route' => 'admin.marketing.search_seo.search_terms.index',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_terms.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.search_terms.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.search_terms.edit',
        'name' => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.search_terms.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_terms.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.marketing.search_seo.search_terms.delete',
            'admin.marketing.search_seo.search_terms.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_synonyms',
        'name' => 'admin::app.acl.search-synonyms',
        'route' => 'admin.marketing.search_seo.search_synonyms.index',
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.search_synonyms.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.edit',
        'name' => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.search_synonyms.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.search_synonyms.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.marketing.search_seo.search_synonyms.delete',
            'admin.marketing.search_seo.search_synonyms.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'marketing.search_seo.sitemaps',
        'name' => 'admin::app.acl.sitemaps',
        'route' => 'admin.marketing.search_seo.sitemaps.index',
        'sort' => 4,
    ], [
        'key' => 'marketing.search_seo.sitemaps.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.marketing.search_seo.sitemaps.store',
        'sort' => 1,
    ], [
        'key' => 'marketing.search_seo.sitemaps.edit',
        'name' => 'admin::app.acl.edit',
        'route' => 'admin.marketing.search_seo.sitemaps.update',
        'sort' => 2,
    ], [
        'key' => 'marketing.search_seo.sitemaps.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.marketing.search_seo.sitemaps.delete',
        'sort' => 3,
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
        'key' => 'reporting',
        'name' => 'admin::app.acl.reporting',
        'route' => 'admin.reporting.sales.index',
        'sort' => 6,
    ], [
        'key' => 'reporting.sales',
        'name' => 'admin::app.acl.sales',
        'route' => 'admin.reporting.sales.index',
        'sort' => 1,
    ], [
        'key' => 'reporting.customers',
        'name' => 'admin::app.acl.customers',
        'route' => 'admin.reporting.customers.index',
        'sort' => 2,
    ], [
        'key' => 'reporting.products',
        'name' => 'admin::app.acl.products',
        'route' => 'admin.reporting.products.index',
        'sort' => 3,
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
        'key' => 'cms',
        'name' => 'admin::app.acl.cms',
        'route' => 'admin.cms.index',
        'sort' => 7,
    ], [
        'key' => 'cms.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.cms.create',
            'admin.cms.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'cms.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.cms.edit',
            'admin.cms.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'cms.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.cms.delete',
            'admin.cms.mass_delete',
        ],
        'sort' => 3,
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
        'key' => 'settings',
        'name' => 'admin::app.acl.settings',
        'route' => 'admin.settings.users.index',
        'sort' => 8,
    ], [
        'key' => 'settings.locales',
        'name' => 'admin::app.acl.locales',
        'route' => 'admin.settings.locales.index',
        'sort' => 1,
    ], [
        'key' => 'settings.locales.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.locales.store',
        'sort' => 1,
    ], [
        'key' => 'settings.locales.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.locales.edit',
            'admin.settings.locales.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.locales.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.locales.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.currencies',
        'name' => 'admin::app.acl.currencies',
        'route' => 'admin.settings.currencies.index',
        'sort' => 2,
    ], [
        'key' => 'settings.currencies.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.currencies.store',
        'sort' => 1,
    ], [
        'key' => 'settings.currencies.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.currencies.edit',
            'admin.settings.currencies.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.currencies.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.settings.currencies.delete',
            'admin.settings.currencies.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'settings.exchange_rates',
        'name' => 'admin::app.acl.exchange-rates',
        'route' => 'admin.settings.exchange_rates.index',
        'sort' => 3,
    ], [
        'key' => 'settings.exchange_rates.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.exchange_rates.store',
        'sort' => 1,
    ], [
        'key' => 'settings.exchange_rates.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.exchange_rates.edit',
            'admin.settings.exchange_rates.update',
            'admin.settings.exchange_rates.update_rates',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.exchange_rates.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.exchange_rates.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.inventory_sources',
        'name' => 'admin::app.acl.inventory-sources',
        'route' => 'admin.settings.inventory_sources.index',
        'sort' => 4,
    ], [
        'key' => 'settings.inventory_sources.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.settings.inventory_sources.create',
            'admin.settings.inventory_sources.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.inventory_sources.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.inventory_sources.edit',
            'admin.settings.inventory_sources.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.inventory_sources.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.inventory_sources.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.channels',
        'name' => 'admin::app.acl.channels',
        'route' => 'admin.settings.channels.index',
        'sort' => 5,
    ], [
        'key' => 'settings.channels.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.settings.channels.create',
            'admin.settings.channels.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.channels.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.channels.edit',
            'admin.settings.channels.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.channels.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.channels.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.users',
        'name' => 'admin::app.acl.users',
        'route' => 'admin.settings.users.index',
        'sort' => 6,
    ], [
        'key' => 'settings.users.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.users.store',
        'sort' => 1,
    ], [
        'key' => 'settings.users.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.users.edit',
            'admin.settings.users.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.users.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.users.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.roles',
        'name' => 'admin::app.acl.roles',
        'route' => 'admin.settings.roles.index',
        'sort' => 7,
    ], [
        'key' => 'settings.roles.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.settings.roles.create',
            'admin.settings.roles.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.roles.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.roles.edit',
            'admin.settings.roles.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.roles.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.roles.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.themes',
        'name' => 'admin::app.acl.themes',
        'route' => 'admin.settings.themes.index',
        'sort' => 8,
    ], [
        'key' => 'settings.themes.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.themes.store',
        'sort' => 1,
    ], [
        'key' => 'settings.themes.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.themes.edit',
            'admin.settings.themes.update',
            'admin.settings.themes.mass_update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.themes.delete',
        'name' => 'admin::app.acl.delete',
        'route' => [
            'admin.settings.themes.delete',
            'admin.settings.themes.mass_delete',
        ],
        'sort' => 3,
    ], [
        'key' => 'settings.taxes',
        'name' => 'admin::app.acl.taxes',
        'route' => 'admin.settings.taxes.categories.index',
        'sort' => 9,
    ], [
        'key' => 'settings.taxes.tax_categories',
        'name' => 'admin::app.acl.tax-categories',
        'route' => 'admin.settings.taxes.categories.index',
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_categories.create',
        'name' => 'admin::app.acl.create',
        'route' => 'admin.settings.taxes.categories.store',
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_categories.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.taxes.categories.edit',
            'admin.settings.taxes.categories.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.taxes.tax_categories.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.taxes.categories.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.taxes.tax_rates',
        'name' => 'admin::app.acl.tax-rates',
        'route' => 'admin.settings.taxes.rates.index',
        'sort' => 2,
    ], [
        'key' => 'settings.taxes.tax_rates.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.settings.taxes.rates.create',
            'admin.settings.taxes.rates.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.taxes.tax_rates.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.taxes.rates.edit',
            'admin.settings.taxes.rates.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.data_transfer',
        'name' => 'admin::app.acl.data-transfer',
        'route' => 'admin.settings.data_transfer.imports.index',
        'sort' => 10,
    ], [
        'key' => 'settings.data_transfer.imports',
        'name' => 'admin::app.acl.imports',
        'route' => 'admin.settings.data_transfer.imports.index',
        'sort' => 1,
    ], [
        'key' => 'settings.data_transfer.imports.create',
        'name' => 'admin::app.acl.create',
        'route' => [
            'admin.settings.data_transfer.imports.create',
            'admin.settings.data_transfer.imports.store',
        ],
        'sort' => 1,
    ], [
        'key' => 'settings.data_transfer.imports.edit',
        'name' => 'admin::app.acl.edit',
        'route' => [
            'admin.settings.data_transfer.imports.edit',
            'admin.settings.data_transfer.imports.update',
        ],
        'sort' => 2,
    ], [
        'key' => 'settings.data_transfer.imports.delete',
        'name' => 'admin::app.acl.delete',
        'route' => 'admin.settings.data_transfer.imports.delete',
        'sort' => 3,
    ], [
        'key' => 'settings.data_transfer.imports.import',
        'name' => 'admin::app.acl.import',
        'route' => 'admin.settings.data_transfer.imports.import',
        'sort' => 4,
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
        'key' => 'configuration',
        'name' => 'admin::app.acl.configure',
        'route' => [
            'admin.configuration.index',
            'admin.configuration.store',
        ],
        'sort' => 9,
    ],
];
