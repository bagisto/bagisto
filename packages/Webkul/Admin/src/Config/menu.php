<?php

return [
    /**
     * Dashboard.
     */
    [
        'key'   => 'dashboard',
        'name'  => 'admin::app.components.layouts.sidebar.dashboard',
        'route' => 'admin.dashboard.index',
        'sort'  => 1,
        'icon'  => 'icon-dashboard',
    ],

    /**
     * Sales.
     */
    [
        'key'   => 'sales',
        'name'  => 'admin::app.components.layouts.sidebar.sales',
        'route' => 'admin.sales.orders.index',
        'sort'  => 2,
        'icon'  => 'icon-sales',
        'items' => [
            [
                'key'   => 'orders',
                'name'  => 'admin::app.components.layouts.sidebar.orders',
                'route' => 'admin.sales.orders.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'shipments',
                'name'  => 'admin::app.components.layouts.sidebar.shipments',
                'route' => 'admin.sales.shipments.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'invoices',
                'name'  => 'admin::app.components.layouts.sidebar.invoices',
                'route' => 'admin.sales.invoices.index',
                'sort'  => 3,
                'icon'  => '',
            ], [
                'key'   => 'refunds',
                'name'  => 'admin::app.components.layouts.sidebar.refunds',
                'route' => 'admin.sales.refunds.index',
                'sort'  => 4,
                'icon'  => '',
            ], [
                'key'   => 'transactions',
                'name'  => 'admin::app.components.layouts.sidebar.transactions',
                'route' => 'admin.sales.transactions.index',
                'sort'  => 5,
                'icon'  => '',
            ],
        ],
    ],

    /**
     * Catalog.
     */
    [
        'key'   => 'catalog',
        'name'  => 'admin::app.components.layouts.sidebar.catalog',
        'route' => 'admin.catalog.products.index',
        'sort'  => 3,
        'icon'  => 'icon-product',
        'items' => [
            [
                'key'   => 'products',
                'name'  => 'admin::app.components.layouts.sidebar.products',
                'route' => 'admin.catalog.products.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'categories',
                'name'  => 'admin::app.components.layouts.sidebar.categories',
                'route' => 'admin.catalog.categories.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'attributes',
                'name'  => 'admin::app.components.layouts.sidebar.attributes',
                'route' => 'admin.catalog.attributes.index',
                'sort'  => 3,
                'icon'  => '',
            ], [
                'key'   => 'families',
                'name'  => 'admin::app.components.layouts.sidebar.attribute-families',
                'route' => 'admin.catalog.families.index',
                'sort'  => 4,
                'icon'  => '',
            ],
        ],
    ],

    /**
     * Customers.
     */
    [
        'key'   => 'customers',
        'name'  => 'admin::app.components.layouts.sidebar.customers',
        'route' => 'admin.customers.customers.index',
        'sort'  => 4,
        'icon'  => 'icon-customer-2',
        'items' => [
            [
                'key'   => 'customers',
                'name'  => 'admin::app.components.layouts.sidebar.customers',
                'route' => 'admin.customers.customers.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'groups',
                'name'  => 'admin::app.components.layouts.sidebar.groups',
                'route' => 'admin.customers.groups.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'reviews',
                'name'  => 'admin::app.components.layouts.sidebar.reviews',
                'route' => 'admin.customers.customers.review.index',
                'sort'  => 3,
                'icon'  => '',
            ],
        ],
    ],

    /**
     * CMS.
     */
    [
        'key'   => 'cms',
        'name'  => 'admin::app.components.layouts.sidebar.cms',
        'route' => 'admin.cms.index',
        'sort'  => 5,
        'icon'  => 'icon-cms',
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
        'items'      => [
            [
                'key'   => 'marketing.promotions',
                'name'  => 'admin::app.components.layouts.sidebar.promotions',
                'route' => 'admin.marketing.promotions.catalog_rules.index',
                'sort'  => 1,
                'icon'  => '',
                'items' => [
                    [
                        'key'   => 'catalog_rules',
                        'name'  => 'admin::app.marketing.promotions.index.catalog-rule-title',
                        'route' => 'admin.marketing.promotions.catalog_rules.index',
                        'sort'  => 1,
                        'icon'  => '',
                    ], [
                        'key'   => 'cart_rules',
                        'name'  => 'admin::app.marketing.promotions.index.cart-rule-title',
                        'route' => 'admin.marketing.promotions.cart_rules.index',
                        'sort'  => 2,
                        'icon'  => '',
                    ],
                ],
            ], [
                'key'   => 'marketing.communications',
                'name'  => 'admin::app.components.layouts.sidebar.communications',
                'route' => 'admin.marketing.communications.email_templates.index',
                'sort'  => 2,
                'icon'  => '',
                'items' => [
                    [
                        'key'   => 'email_templates',
                        'name'  => 'admin::app.components.layouts.sidebar.email-templates',
                        'route' => 'admin.marketing.communications.email_templates.index',
                        'sort'  => 1,
                        'icon'  => '',
                    ], [
                        'key'   => 'events',
                        'name'  => 'admin::app.components.layouts.sidebar.events',
                        'route' => 'admin.marketing.communications.events.index',
                        'sort'  => 2,
                        'icon'  => '',
                    ], [
                        'key'   => 'campaigns',
                        'name'  => 'admin::app.components.layouts.sidebar.campaigns',
                        'route' => 'admin.marketing.communications.campaigns.index',
                        'sort'  => 2,
                        'icon'  => '',
                    ], [
                        'key'   => 'subscribers',
                        'name'  => 'admin::app.components.layouts.sidebar.newsletter-subscriptions',
                        'route' => 'admin.marketing.communications.subscribers.index',
                        'sort'  => 3,
                        'icon'  => '',
                    ],
                ],
            ], [
                'key'   => 'marketing.search_seo',
                'name'  => 'admin::app.components.layouts.sidebar.search-seo',
                'route' => 'admin.marketing.search_seo.url_rewrites.index',
                'sort'  => 3,
                'icon'  => '',
                'items' => [
                    [
                        'key'   => 'url_rewrites',
                        'name'  => 'admin::app.components.layouts.sidebar.url-rewrites',
                        'route' => 'admin.marketing.search_seo.url_rewrites.index',
                        'sort'  => 1,
                        'icon'  => '',
                    ], [
                        'key'   => 'search_terms',
                        'name'  => 'admin::app.components.layouts.sidebar.search-terms',
                        'route' => 'admin.marketing.search_seo.search_terms.index',
                        'sort'  => 2,
                        'icon'  => '',
                    ], [
                        'key'   => 'search_synonyms',
                        'name'  => 'admin::app.components.layouts.sidebar.search-synonyms',
                        'route' => 'admin.marketing.search_seo.search_synonyms.index',
                        'sort'  => 3,
                        'icon'  => '',
                    ], [
                        'key'   => 'sitemaps',
                        'name'  => 'admin::app.components.layouts.sidebar.sitemaps',
                        'route' => 'admin.marketing.search_seo.sitemaps.index',
                        'sort'  => 4,
                        'icon'  => '',
                    ],
                ],
            ],
        ],
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
        'items'      => [
            [
                'key'   => 'sales',
                'name'  => 'admin::app.components.layouts.sidebar.sales',
                'route' => 'admin.reporting.sales.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'customers',
                'name'  => 'admin::app.components.layouts.sidebar.customers',
                'route' => 'admin.reporting.customers.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'products',
                'name'  => 'admin::app.components.layouts.sidebar.products',
                'route' => 'admin.reporting.products.index',
                'sort'  => 3,
                'icon'  => '',
            ],
        ],
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
        'items'      => [
            [
                'key'   => 'locales',
                'name'  => 'admin::app.components.layouts.sidebar.locales',
                'route' => 'admin.settings.locales.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'currencies',
                'name'  => 'admin::app.components.layouts.sidebar.currencies',
                'route' => 'admin.settings.currencies.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'exchange_rates',
                'name'  => 'admin::app.components.layouts.sidebar.exchange-rates',
                'route' => 'admin.settings.exchange_rates.index',
                'sort'  => 3,
                'icon'  => '',
            ], [
                'key'   => 'inventory_sources',
                'name'  => 'admin::app.components.layouts.sidebar.inventory-sources',
                'route' => 'admin.settings.inventory_sources.index',
                'sort'  => 4,
                'icon'  => '',
            ], [
                'key'   => 'channels',
                'name'  => 'admin::app.components.layouts.sidebar.channels',
                'route' => 'admin.settings.channels.index',
                'sort'  => 5,
                'icon'  => '',
            ], [
                'key'   => 'users',
                'name'  => 'admin::app.components.layouts.sidebar.users',
                'route' => 'admin.settings.users.index',
                'sort'  => 6,
                'icon'  => '',
            ], [
                'key'   => 'roles',
                'name'  => 'admin::app.components.layouts.sidebar.roles',
                'route' => 'admin.settings.roles.index',
                'sort'  => 7,
                'icon'  => '',
            ], [
                'key'   => 'themes',
                'name'  => 'admin::app.components.layouts.sidebar.themes',
                'route' => 'admin.settings.themes.index',
                'sort'  => 8,
                'icon'  => '',
            ], [
                'key'   => 'taxes',
                'name'  => 'admin::app.components.layouts.sidebar.taxes',
                'route' => 'admin.settings.taxes.categories.index',
                'sort'  => 9,
                'icon'  => '',
            ], [
                'key'   => 'taxes.tax_categories',
                'name'  => 'admin::app.components.layouts.sidebar.tax-categories',
                'route' => 'admin.settings.taxes.categories.index',
                'sort'  => 1,
                'icon'  => '',
            ], [
                'key'   => 'taxes.tax_rates',
                'name'  => 'admin::app.components.layouts.sidebar.tax-rates',
                'route' => 'admin.settings.taxes.rates.index',
                'sort'  => 2,
                'icon'  => '',
            ], [
                'key'   => 'data_transfer',
                'name'  => 'admin::app.components.layouts.sidebar.data-transfer',
                'route' => 'admin.settings.data_transfer.imports.index',
                'sort'  => 10,
                'icon'  => '',
            ], [
                'key'   => 'data_transfer.imports',
                'name'  => 'admin::app.components.layouts.sidebar.imports',
                'route' => 'admin.settings.data_transfer.imports.index',
                'sort'  => 1,
                'icon'  => '',
            ],
        ],
    ],

    /**
     * Configuration.
     */
    [
        'key'   => 'configuration',
        'name'  => 'admin::app.components.layouts.sidebar.configure',
        'route' => 'admin.configuration.index',
        'sort'  => 9,
        'icon'  => 'icon-configuration',
    ],
];
