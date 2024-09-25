<?php

use Webkul\Sales\Models\Order;

return [
    /**
     * General.
     */
    [
        'key'  => 'general',
        'name' => 'admin::app.configuration.index.general.title',
        'info' => 'admin::app.configuration.index.general.info',
        'sort' => 1,
    ], [
        'key'  => 'general.general',
        'name' => 'admin::app.configuration.index.general.general.title',
        'info' => 'admin::app.configuration.index.general.general.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'general.general.locale_options',
        'name'   => 'admin::app.configuration.index.general.general.unit-options.title',
        'info'   => 'admin::app.configuration.index.general.general.unit-options.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'weight_unit',
                'title'         => 'admin::app.configuration.index.general.general.unit-options.weight-unit',
                'type'          => 'select',
                'default'       => 'kgs',
                'options'       => [
                    [
                        'title' => 'lbs',
                        'value' => 'lbs',
                    ], [
                        'title' => 'kgs',
                        'value' => 'kgs',
                    ],
                ],
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.general.breadcrumbs',
        'name'   => 'admin::app.configuration.index.general.general.breadcrumbs.title',
        'info'   => 'admin::app.configuration.index.general.general.breadcrumbs.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'shop',
                'title'   => 'admin::app.configuration.index.general.general.breadcrumbs.shop',
                'type'    => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key'  => 'general.content',
        'name' => 'admin::app.configuration.index.general.content.title',
        'info' => 'admin::app.configuration.index.general.content.info',
        'icon' => 'settings/store.svg',
        'sort' => 2,
    ], [
        'key'    => 'general.content.header_offer',
        'name'   => 'admin::app.configuration.index.general.content.header-offer.title',
        'info'   => 'admin::app.configuration.index.general.content.header-offer.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'title',
                'title'   => 'admin::app.configuration.index.general.content.header-offer.offer-title',
                'type'    => 'text',
                'default' => 'Get UPTO 40% OFF on your 1st order',
            ], [
                'name'    => 'redirection_title',
                'title'   => 'admin::app.configuration.index.general.content.header-offer.redirection-title',
                'type'    => 'text',
                'default' => 'SHOP NOW',
            ], [
                'name'    => 'redirection_link',
                'title'   => 'admin::app.configuration.index.general.content.header-offer.redirection-link',
                'type'    => 'text',
            ],
        ],
    ], [
        'key'    => 'general.content.custom_scripts',
        'name'   => 'admin::app.configuration.index.general.content.custom-scripts.title',
        'info'   => 'admin::app.configuration.index.general.content.custom-scripts.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'custom_css',
                'title'         => 'admin::app.configuration.index.general.content.custom-scripts.custom-css',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'custom_javascript',
                'title'         => 'admin::app.configuration.index.general.content.custom-scripts.custom-javascript',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'general.design',
        'name' => 'admin::app.configuration.index.general.design.title',
        'info' => 'admin::app.configuration.index.general.design.info',
        'icon' => 'settings/theme.svg',
        'sort' => 3,
    ], [
        'key'    => 'general.design.admin_logo',
        'name'   => 'admin::app.configuration.index.general.design.admin-logo.title',
        'info'   => 'admin::app.configuration.index.general.design.admin-logo.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'logo_image',
                'title'         => 'admin::app.configuration.index.general.design.admin-logo.logo-image',
                'type'          => 'image',
                'channel_based' => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ], [
                'name'          => 'favicon',
                'title'         => 'admin::app.configuration.index.general.design.admin-logo.favicon',
                'type'          => 'image',
                'channel_based' => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp,svg,ico',
            ],
        ],
    ], [
        'key'  => 'general.magic_ai',
        'name' => 'admin::app.configuration.index.general.magic-ai.title',
        'info' => 'admin::app.configuration.index.general.magic-ai.info',
        'icon' => 'settings/magic-ai.svg',
        'sort' => 3,
    ], [
        'key'    => 'general.magic_ai.settings',
        'name'   => 'admin::app.configuration.index.general.magic-ai.settings.title',
        'info'   => 'admin::app.configuration.index.general.magic-ai.settings.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'admin::app.configuration.index.general.magic-ai.settings.enabled',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'api_key',
                'title'         => 'admin::app.configuration.index.general.magic-ai.settings.api-key',
                'type'          => 'password',
                'channel_based' => true,
            ], [
                'name'          => 'organization',
                'title'         => 'admin::app.configuration.index.general.magic-ai.settings.organization',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'api_domain',
                'title'         => 'admin::app.configuration.index.general.magic-ai.settings.llm-api-domain',
                'type'          => 'text',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.magic_ai.content_generation',
        'name'   => 'admin::app.configuration.index.general.magic-ai.content-generation.title',
        'info'   => 'admin::app.configuration.index.general.magic-ai.content-generation.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enabled',
                'title' => 'admin::app.configuration.index.general.magic-ai.content-generation.enabled',
                'type'  => 'boolean',
            ], [
                'name'         => 'product_short_description_prompt',
                'title'        => 'admin::app.configuration.index.general.magic-ai.content-generation.product-short-description-prompt',
                'type'         => 'textarea',
                'locale_based' => true,
            ], [
                'name'         => 'product_description_prompt',
                'title'        => 'admin::app.configuration.index.general.magic-ai.content-generation.product-description-prompt',
                'type'         => 'textarea',
                'locale_based' => true,
            ], [
                'name'         => 'category_description_prompt',
                'title'        => 'admin::app.configuration.index.general.magic-ai.content-generation.category-description-prompt',
                'type'         => 'textarea',
                'locale_based' => true,
            ], [
                'name'         => 'cms_page_content_prompt',
                'title'        => 'admin::app.configuration.index.general.magic-ai.content-generation.cms-page-content-prompt',
                'type'         => 'textarea',
                'locale_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.magic_ai.image_generation',
        'name'   => 'admin::app.configuration.index.general.magic-ai.image-generation.title',
        'info'   => 'admin::app.configuration.index.general.magic-ai.image-generation.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'admin::app.configuration.index.general.magic-ai.image-generation.enabled',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'general.magic_ai.review_translation',
        'name'   => 'admin::app.configuration.index.general.magic-ai.review-translation.title',
        'info'   => 'admin::app.configuration.index.general.magic-ai.review-translation.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'admin::app.configuration.index.general.magic-ai.review-translation.enabled',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'model',
                'title'         => 'admin::app.configuration.index.general.magic-ai.review-translation.model',
                'type'          => 'select',
                'channel_based' => true,
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.gpt-3-5-turbo',
                        'value' => 'gpt-3.5-turbo',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.llama2',
                        'value' => 'llama2',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.mistral',
                        'value' => 'mistral',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.dolphin-phi',
                        'value' => 'dolphin-phi',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.phi',
                        'value' => 'phi',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.starling-lm',
                        'value' => 'starling-lm',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.llama2-uncensored',
                        'value' => 'llama2-uncensored',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.llama2:13b',
                        'value' => 'llama2:13b',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.llama2:70b',
                        'value' => 'llama2:70b',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.orca-mini',
                        'value' => 'orca-mini',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.vicuna',
                        'value' => 'vicuna',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.review-translation.llava',
                        'value' => 'llava',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'general.magic_ai.checkout_message',
        'name'   => 'admin::app.configuration.index.general.magic-ai.checkout-message.title',
        'info'   => 'admin::app.configuration.index.general.magic-ai.checkout-message.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enabled',
                'title'         => 'admin::app.configuration.index.general.magic-ai.checkout-message.enabled',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'model',
                'title'         => 'admin::app.configuration.index.general.magic-ai.checkout-message.model',
                'type'          => 'select',
                'channel_based' => true,
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.gpt-3-5-turbo',
                        'value' => 'open-ai-gpt-3.5-turbo',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.llama2',
                        'value' => 'llama2',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.mistral',
                        'value' => 'mistral',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.dolphin-phi',
                        'value' => 'dolphin-phi',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.phi',
                        'value' => 'phi',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.starling-lm',
                        'value' => 'starling-lm',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.llama2-uncensored',
                        'value' => 'llama2-uncensored',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.llama2:13b',
                        'value' => 'llama2:13b',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.llama2:70b',
                        'value' => 'llama2:70b',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.orca-mini',
                        'value' => 'orca-mini',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.vicuna',
                        'value' => 'vicuna',
                    ], [
                        'title' => 'admin::app.configuration.index.general.magic-ai.checkout-message.llava',
                        'value' => 'llava',
                    ],
                ],
            ], [
                'name'          => 'prompt',
                'title'         => 'admin::app.configuration.index.general.magic-ai.checkout-message.prompt',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ],

    /**
     * Catalog.
     */
    [
        'key'  => 'catalog',
        'name' => 'admin::app.configuration.index.catalog.title',
        'info' => 'admin::app.configuration.index.catalog.info',
        'sort' => 2,
    ], [
        'key'  => 'catalog.products',
        'name' => 'admin::app.configuration.index.catalog.products.title',
        'info' => 'admin::app.configuration.index.catalog.products.info',
        'icon' => 'settings/product.svg',
        'sort' => 1,
    ], [
        'key'    => 'catalog.products.settings',
        'name'   => 'admin::app.configuration.index.catalog.products.settings.title',
        'info'   => 'admin::app.configuration.index.catalog.products.settings.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'compare_option',
                'title'         => 'admin::app.configuration.index.catalog.products.settings.compare-options',
                'type'          => 'boolean',
                'default'       => 1,
            ], [
                'name'          => 'image_search',
                'title'         => 'admin::app.configuration.index.catalog.products.settings.image-search-option',
                'type'          => 'boolean',
                'default'       => 1,
            ],
        ],
    ], [
        'key'    => 'catalog.products.search',
        'name'   => 'admin::app.configuration.index.catalog.products.search.title',
        'info'   => 'admin::app.configuration.index.catalog.products.search.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'engine',
                'title'   => 'admin::app.configuration.index.catalog.products.search.search-engine',
                'type'    => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'    => 'admin_mode',
                'title'   => 'admin::app.configuration.index.catalog.products.search.admin-mode',
                'info'    => 'admin::app.configuration.index.catalog.products.search.admin-mode-info',
                'type'    => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'    => 'storefront_mode',
                'title'   => 'admin::app.configuration.index.catalog.products.search.storefront-mode',
                'info'    => 'admin::app.configuration.index.catalog.products.search.storefront-mode-info',
                'type'    => 'select',
                'default' => 'database',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.search.database',
                        'value' => 'database',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.search.elastic',
                        'value' => 'elastic',
                    ],
                ],
            ], [
                'name'       => 'min_query_length',
                'title'      => 'admin::app.configuration.index.catalog.products.search.min-query-length',
                'info'       => 'admin::app.configuration.index.catalog.products.search.min-query-length-info',
                'type'       => 'number',
                'validation' => 'numeric',
                'default'    => '0',
            ], [
                'name'       => 'max_query_length',
                'title'      => 'admin::app.configuration.index.catalog.products.search.max-query-length',
                'info'       => 'admin::app.configuration.index.catalog.products.search.max-query-length-info',
                'type'       => 'number',
                'validation' => 'numeric',
                'default'    => '1000',
            ],
        ],
    ], [
        'key'    => 'catalog.products.product_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.product-view-page.title',
        'info'   => 'admin::app.configuration.index.catalog.products.product-view-page.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'       => 'no_of_related_products',
                'title'      => 'admin::app.configuration.index.catalog.products.product-view-page.allow-no-of-related-products',
                'type'       => 'number',
                'validation' => 'integer|min:0',
            ], [
                'name'       => 'no_of_up_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.product-view-page.allow-no-of-up-sells-products',
                'type'       => 'number',
                'validation' => 'integer|min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cart_view_page',
        'name'   => 'admin::app.configuration.index.catalog.products.cart-view-page.title',
        'info'   => 'admin::app.configuration.index.catalog.products.cart-view-page.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'       => 'no_of_cross_sells_products',
                'title'      => 'admin::app.configuration.index.catalog.products.cart-view-page.allow-no-of-cross-sells-products',
                'type'       => 'number',
                'validation' => 'integer|min:0',
            ],
        ],
    ], [
        'key'    => 'catalog.products.storefront',
        'name'   => 'admin::app.configuration.index.catalog.products.storefront.title',
        'info'   => 'admin::app.configuration.index.catalog.products.storefront.title-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'          => 'mode',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.default-list-mode',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.grid',
                        'value' => 'grid',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.list',
                        'value' => 'list',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'          => 'products_per_page',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.products-per-page',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.catalog.products.storefront.comma-separated',
                'channel_based' => true,
            ], [
                'name'          => 'sort_by',
                'title'         => 'admin::app.configuration.index.catalog.products.storefront.sort-by',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.from-a-z',
                        'value' => 'name-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.from-z-a',
                        'value' => 'name-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.latest-first',
                        'value' => 'created_at-desc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.oldest-first',
                        'value' => 'created_at-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.cheapest-first',
                        'value' => 'price-asc',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.storefront.expensive-first',
                        'value' => 'price-desc',
                    ],
                ],
                'channel_based' => true,
            ], [
                'name'  => 'buy_now_button_display',
                'title' => 'admin::app.configuration.index.catalog.products.storefront.buy-now-button-display',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_small_image',
        'name'   => 'admin::app.configuration.index.catalog.products.small-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.small-image.title-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'       => 'width',
                'title'      => 'admin::app.configuration.index.catalog.products.small-image.width',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'height',
                'title'      => 'admin::app.configuration.index.catalog.products.small-image.height',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'url',
                'title'      => 'admin::app.configuration.index.catalog.products.small-image.placeholder',
                'type'       => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_medium_image',
        'name'   => 'admin::app.configuration.index.catalog.products.medium-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.medium-image.title-info',
        'sort'   => 6,
        'fields' => [
            [
                'name'       => 'width',
                'title'      => 'admin::app.configuration.index.catalog.products.medium-image.width',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'height',
                'title'      => 'admin::app.configuration.index.catalog.products.medium-image.height',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'url',
                'title'      => 'admin::app.configuration.index.catalog.products.medium-image.placeholder',
                'type'       => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key'    => 'catalog.products.cache_large_image',
        'name'   => 'admin::app.configuration.index.catalog.products.large-image.title',
        'info'   => 'admin::app.configuration.index.catalog.products.large-image.title-info',
        'sort'   => 7,
        'fields' => [
            [
                'name'       => 'width',
                'title'      => 'admin::app.configuration.index.catalog.products.large-image.width',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'height',
                'title'      => 'admin::app.configuration.index.catalog.products.large-image.height',
                'type'       => 'text',
                'validation' => 'integer|min:1',
            ], [
                'name'       => 'url',
                'title'      => 'admin::app.configuration.index.catalog.products.large-image.placeholder',
                'type'       => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp,svg',
            ],
        ],
    ], [
        'key'    => 'catalog.products.review',
        'name'   => 'admin::app.configuration.index.catalog.products.review.title',
        'info'   => 'admin::app.configuration.index.catalog.products.review.title-info',
        'sort'   => 8,
        'fields' => [
            [
                'name'  => 'guest_review',
                'title' => 'admin::app.configuration.index.catalog.products.review.allow-guest-review',
                'type'  => 'boolean',
            ], [
                'name'    => 'customer_review',
                'title'   => 'admin::app.configuration.index.catalog.products.review.allow-customer-review',
                'type'    => 'boolean',
                'default' => true,
            ], [
                'name'    => 'summary',
                'title'   => 'admin::app.configuration.index.catalog.products.review.summary',
                'type'    => 'select',
                'default' => 'review_counts',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.catalog.products.review.display-star-count',
                        'value' => 'star_counts',
                    ], [
                        'title' => 'admin::app.configuration.index.catalog.products.review.display-review-count',
                        'value' => 'review_counts',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'catalog.products.attribute',
        'name'   => 'admin::app.configuration.index.catalog.products.attribute.title',
        'info'   => 'admin::app.configuration.index.catalog.products.attribute.title-info',
        'sort'   => 9,
        'fields' => [
            [
                'name'       => 'image_attribute_upload_size',
                'title'      => 'admin::app.configuration.index.catalog.products.attribute.image-upload-size',
                'type'       => 'text',
                'validation' => 'numeric',
            ], [
                'name'       => 'file_attribute_upload_size',
                'title'      => 'admin::app.configuration.index.catalog.products.attribute.file-upload-size',
                'type'       => 'text',
                'validation' => 'numeric',
            ],
        ],
    ], [
        'key'    => 'catalog.products.social_share',
        'name'   => 'admin::app.configuration.index.catalog.products.social-share.title',
        'info'   => 'admin::app.configuration.index.catalog.products.social-share.title-info',
        'sort'   => 10,
        'fields' => [
            [
                'name'  => 'enabled',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-social-share',
                'type'  => 'boolean',
            ], [
                'name'  => 'facebook',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-facebook',
                'type'  => 'boolean',
            ], [
                'name'  => 'twitter',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-twitter',
                'type'  => 'boolean',
            ], [
                'name'  => 'pinterest',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-pinterest',
                'type'  => 'boolean',
            ], [
                'name'  => 'whatsapp',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-whatsapp',
                'type'  => 'boolean',
                'info'  => 'admin::app.configuration.index.catalog.products.social-share.enable-share-whatsapp-info',
            ], [
                'name'  => 'linkedin',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-linkedin',
                'type'  => 'boolean',
            ], [
                'name'  => 'email',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.enable-share-email',
                'type'  => 'boolean',
            ], [
                'name'  => 'share_message',
                'title' => 'admin::app.configuration.index.catalog.products.social-share.share-message',
                'type'  => 'text',
            ],
        ],
    ], [
        'key'  => 'catalog.rich_snippets',
        'name' => 'admin::app.configuration.index.catalog.rich-snippets.title',
        'info' => 'admin::app.configuration.index.catalog.rich-snippets.info',
        'icon' => 'settings/settings.svg',
        'sort' => 2,
    ], [
        'key'    => 'catalog.rich_snippets.products',
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.products.title',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.products.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_sku',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-sku',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_weight',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-weight',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_categories',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-categories',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_images',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-images',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_reviews',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-reviews',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_ratings',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-ratings',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_offers',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.products.show-offers',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'catalog.rich_snippets.categories',
        'name'   => 'admin::app.configuration.index.catalog.rich-snippets.categories.title',
        'info'   => 'admin::app.configuration.index.catalog.rich-snippets.categories.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'enable',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.categories.enable',
                'type'  => 'boolean',
            ], [
                'name'  => 'show_search_input_field',
                'title' => 'admin::app.configuration.index.catalog.rich-snippets.categories.show-search-input-field',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'  => 'catalog.inventory',
        'name' => 'admin::app.configuration.index.catalog.inventory.title',
        'info' => 'admin::app.configuration.index.catalog.inventory.title-info',
        'icon' => 'settings/inventory.svg',
        'sort' => 3,
    ], [
        'key'    => 'catalog.inventory.stock_options',
        'name'   => 'admin::app.configuration.index.catalog.inventory.product-stock-options.title',
        'info'   => 'admin::app.configuration.index.catalog.inventory.product-stock-options.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'back_orders',
                'title'         => 'admin::app.configuration.index.catalog.inventory.product-stock-options.allow-back-orders',
                'type'          => 'boolean',
                'default',
            ],
            // [
            //     'name'          => 'maximum_product',
            //     'title'         => 'admin::app.configuration.index.catalog.inventory.product-stock-options.max-qty-allowed-in-cart',
            //     'type'          => 'text',
            //     'default'       => '10',
            // ], [
            //     'name'          => 'minimum_product',
            //     'title'         => 'admin::app.configuration.index.catalog.inventory.product-stock-options.min-qty-allowed-in-cart',
            //     'type'          => 'number',
            //     'default'       => '0',
            // ],
            [
                'name'          => 'out_of_stock_threshold',
                'title'         => 'admin::app.configuration.index.catalog.inventory.product-stock-options.out-of-stock-threshold',
                'type'          => 'number',
                'default'       => '0',
            ],
        ],
    ],

    /**
     * Customer.
     */
    [
        'key'  => 'customer',
        'name' => 'admin::app.configuration.index.customer.title',
        'info' => 'admin::app.configuration.index.customer.info',
        'sort' => 3,
    ], [
        'key'  => 'customer.address',
        'name' => 'admin::app.configuration.index.customer.address.title',
        'info' => 'admin::app.configuration.index.customer.address.info',
        'icon' => 'settings/address.svg',
        'sort' => 1,
    ], [
        'key'    => 'customer.address.requirements',
        'name'   => 'admin::app.configuration.index.customer.address.requirements.title',
        'info'   => 'admin::app.configuration.index.customer.address.requirements.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.country',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => 1,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.state',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => 1,
            ], [
                'name'          => 'postcode',
                'title'         => 'admin::app.configuration.index.customer.address.requirements.zip',
                'type'          => 'boolean',
                'channel_based' => true,
                'default'       => 1,
            ],
        ],
    ], [
        'key'    => 'customer.address.information',
        'name'   => 'admin::app.configuration.index.customer.address.information.title',
        'info'   => 'admin::app.configuration.index.customer.address.information.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'street_lines',
                'title'         => 'admin::app.configuration.index.customer.address.information.street-lines',
                'type'          => 'text',
                'validation'    => 'between:1,4',
                'channel_based' => true,
                'default_value' => 1,
            ],
        ],
    ], [
        'key'  => 'customer.captcha',
        'name' => 'admin::app.configuration.index.customer.captcha.title',
        'info' => 'admin::app.configuration.index.customer.captcha.info',
        'icon' => 'settings/captcha.svg',
        'sort' => 2,
    ], [
        'key'    => 'customer.captcha.credentials',
        'name'   => 'admin::app.configuration.index.customer.captcha.credentials.title',
        'info'   => 'admin::app.configuration.index.customer.captcha.credentials.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'site_key',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.site-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'secret_key',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.secret-key',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'status',
                'title'         => 'admin::app.configuration.index.customer.captcha.credentials.status',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'  => 'customer.settings',
        'name' => 'admin::app.configuration.index.customer.settings.title',
        'info' => 'admin::app.configuration.index.customer.settings.settings-info',
        'icon' => 'settings/settings.svg',
        'sort' => 3,
    ],
    // [
    //     'key'    => 'customer.settings.login_as_customer',
    //     'name'   => 'admin::app.configuration.index.customer.settings.login-as-customer.title',
    //     'info'   => 'admin::app.configuration.index.customer.settings.login-as-customer.title-info',
    //     'sort'   => 1,
    //     'fields' => [
    //         [
    //             'name'         => 'login',
    //             'title'        => 'admin::app.configuration.index.customer.settings.login-as-customer.allow-option',
    //             'type'         => 'boolean',
    //             'default'      => 1,
    //         ],
    //     ],
    // ],
    [
        'key'    => 'customer.settings.wishlist',
        'name'   => 'admin::app.configuration.index.customer.settings.wishlist.title',
        'info'   => 'admin::app.configuration.index.customer.settings.wishlist.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'wishlist_option',
                'title'   => 'admin::app.configuration.index.customer.settings.wishlist.allow-option',
                'type'    => 'boolean',
                'default' => 1,
            ],
        ],
    ], [
        'key'    => 'customer.settings.login_options',
        'name'   => 'admin::app.configuration.index.customer.settings.login-options.title',
        'info'   => 'admin::app.configuration.index.customer.settings.login-options.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'    => 'redirected_to_page',
                'title'   => 'admin::app.configuration.index.customer.settings.login-options.redirect-to-page',
                'type'    => 'select',
                'default' => 'home',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.customer.settings.login-options.home',
                        'value' => 'home',
                    ], [
                        'title' => 'admin::app.configuration.index.customer.settings.login-options.account',
                        'value' => 'account',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'customer.settings.create_new_account_options',
        'name'   => 'admin::app.configuration.index.customer.settings.create-new-account-option.title',
        'info'   => 'admin::app.configuration.index.customer.settings.create-new-account-option.title-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'    => 'default_group',
                'title'   => 'admin::app.configuration.index.customer.settings.create-new-account-option.default-group.title',
                'info'    => 'admin::app.configuration.index.customer.settings.create-new-account-option.default-group.title-info',
                'type'    => 'select',
                'default' => 'guest',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.customer.settings.create-new-account-option.default-group.general',
                        'value' => 'general',
                    ], [
                        'title' => 'admin::app.configuration.index.customer.settings.create-new-account-option.default-group.guest',
                        'value' => 'guest',
                    ], [
                        'title' => 'admin::app.configuration.index.customer.settings.create-new-account-option.default-group.wholesale',
                        'value' => 'wholesale',
                    ],
                ],
            ], [
                'name'    => 'news_letter',
                'title'   => 'admin::app.configuration.index.customer.settings.create-new-account-option.news-letter',
                'info'    => 'admin::app.configuration.index.customer.settings.create-new-account-option.news-letter-info',
                'type'    => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key'    => 'customer.settings.newsletter',
        'name'   => 'admin::app.configuration.index.customer.settings.newsletter.title',
        'info'   => 'admin::app.configuration.index.customer.settings.newsletter.title-info',
        'sort'   => 5,
        'fields' => [
            [
                'name'         => 'subscription',
                'title'        => 'admin::app.configuration.index.customer.settings.newsletter.subscription',
                'info'         => 'Enable subscription option for users in the footer section.',
                'type'         => 'boolean',
                'default'      => 1,
            ],
        ],
    ], [
        'key'    => 'customer.settings.email',
        'name'   => 'admin::app.configuration.index.customer.settings.email.title',
        'info'   => 'admin::app.configuration.index.customer.settings.email.title-info',
        'sort'   => 6,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.configuration.index.customer.settings.email.email-verification',
                'type'  => 'boolean',
            ],
        ],
    ], [
        'key'    => 'customer.settings.social_login',
        'name'   => 'admin::app.configuration.index.customer.settings.social-login.social-login',
        'info'   => 'admin::app.configuration.index.customer.settings.social-login.social-login-info',
        'sort'   => 7,
        'fields' => [
            [
                'name'          => 'enable_facebook',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-facebook',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_twitter',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-twitter',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_google',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-google',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_linkedin-openid',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-linkedin',
                'type'          => 'boolean',
                'channel_based' => true,
            ], [
                'name'          => 'enable_github',
                'title'         => 'admin::app.configuration.index.customer.settings.social-login.enable-github',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
        ],
    ],

    /**
     * Emails.
     */
    [
        'key'  => 'emails',
        'name' => 'admin::app.configuration.index.email.title',
        'info' => 'admin::app.configuration.index.email.info',
        'sort' => 4,
    ], [
        'key'  => 'emails.configure',
        'name' => 'admin::app.configuration.index.email.email-settings.title',
        'info' => 'admin::app.configuration.index.email.email-settings.info',
        'icon' => 'settings/email.svg',
        'sort' => 1,
    ], [
        'key'    => 'emails.configure.email_settings',
        'name'   => 'admin::app.configuration.index.email.email-settings.title',
        'info'   => 'admin::app.configuration.index.email.email-settings.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'sender_name',
                'title'         => 'admin::app.configuration.index.email.email-settings.email-sender-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.email-sender-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.from.name'),
            ], [
                'name'          => 'shop_email_from',
                'title'         => 'admin::app.configuration.index.email.email-settings.shop-email-from',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.shop-email-from-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.from.address'),
            ], [
                'name'          => 'admin_name',
                'title'         => 'admin::app.configuration.index.email.email-settings.admin-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.admin-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.admin.name'),
            ], [
                'name'          => 'admin_email',
                'title'         => 'admin::app.configuration.index.email.email-settings.admin-email',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.admin-email-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.admin.address'),
            ], [
                'name'          => 'contact_name',
                'title'         => 'admin::app.configuration.index.email.email-settings.contact-name',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.contact-name-tip',
                'validation'    => 'required|max:50',
                'channel_based' => true,
                'default_value' => config('mail.contact.name'),
            ], [
                'name'          => 'contact_email',
                'title'         => 'admin::app.configuration.index.email.email-settings.contact-email',
                'type'          => 'text',
                'info'          => 'admin::app.configuration.index.email.email-settings.contact-email-tip',
                'validation'    => 'required|email',
                'channel_based' => true,
                'default_value' => config('mail.contact.address'),
            ],
        ],
    ], [
        'key'  => 'emails.general',
        'name' => 'admin::app.configuration.index.email.notifications.title',
        'info' => 'admin::app.configuration.index.email.notifications.info',
        'icon' => 'settings/store.svg',
        'sort' => 1,
    ], [
        'key'    => 'emails.general.notifications',
        'name'   => 'admin::app.configuration.index.email.notifications.title',
        'info'   => 'admin::app.configuration.index.email.notifications.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'emails.general.notifications.verification',
                'title' => 'admin::app.configuration.index.email.notifications.verification',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.registration',
                'title' => 'admin::app.configuration.index.email.notifications.registration',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.customer_registration_confirmation_mail_to_admin',
                'title' => 'admin::app.configuration.index.email.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.customer',
                'title' => 'admin::app.configuration.index.email.notifications.customer',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_order',
                'title' => 'admin::app.configuration.index.email.notifications.new-order',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_admin',
                'title' => 'admin::app.configuration.index.email.notifications.new-admin',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_invoice',
                'title' => 'admin::app.configuration.index.email.notifications.new-invoice',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_refund',
                'title' => 'admin::app.configuration.index.email.notifications.new-refund',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_shipment',
                'title' => 'admin::app.configuration.index.email.notifications.new-shipment',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.new_inventory_source',
                'title' => 'admin::app.configuration.index.email.notifications.new-inventory-source',
                'type'  => 'boolean',
            ], [
                'name'  => 'emails.general.notifications.cancel_order',
                'title' => 'admin::app.configuration.index.email.notifications.cancel-order',
                'type'  => 'boolean',
            ],
        ],
    ],

    /**
     * Sales.
     */
    [
        'key'  => 'sales',
        'name' => 'admin::app.configuration.index.sales.title',
        'info' => 'admin::app.configuration.index.sales.info',
        'sort' => 5,
    ], [
        'key'  => 'sales.shipping',
        'name' => 'admin::app.configuration.index.sales.shipping-setting.title',
        'info' => 'admin::app.configuration.index.sales.shipping-setting.info',
        'icon' => 'settings/shipping.svg',
        'sort' => 1,
    ], [
        'key'    => 'sales.shipping.origin',
        'name'   => 'admin::app.configuration.index.sales.shipping-setting.origin.title',
        'info'   => 'admin::app.configuration.index.sales.shipping-setting.origin.title-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'country',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.country',
                'type'          => 'country',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'state',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.state',
                'type'          => 'state',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'city',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.city',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'address',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.street-address',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'zipcode',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.zip',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'store_name',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.store-name',
                'type'          => 'text',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'vat_number',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.vat-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'contact',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.contact-number',
                'type'          => 'text',
                'channel_based' => true,
            ], [
                'name'          => 'bank_details',
                'title'         => 'admin::app.configuration.index.sales.shipping-setting.origin.bank-details',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'  => 'sales.carriers',
        'name' => 'admin::app.configuration.index.sales.shipping-methods.title',
        'info' => 'admin::app.configuration.index.sales.shipping-methods.info',
        'icon' => 'settings/shipping-method.svg',
        'sort' => 2,
    ], [
        'key'    => 'sales.carriers.free',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.page-title',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.free-shipping.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'    => 'sales.carriers.flatrate',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.page-title',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'default_rate',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.rate',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'type',
                'title'   => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.title',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.per-unit',
                        'value' => 'per_unit',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.type.per-order',
                        'value' => 'per_order',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.flat-rate-shipping.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ], [
        'key'  => 'sales.payment_methods',
        'name' => 'admin::app.configuration.index.sales.payment-methods.page-title',
        'info' => 'admin::app.configuration.index.sales.payment-methods.info',
        'icon' => 'settings/payment-method.svg',
        'sort' => 3,
    ], [
        'key'    => 'sales.payment_methods.cashondelivery',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.cash-on-delivery-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.logo',
                'type'          => 'image',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => true,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'instructions',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.instructions',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'invoice_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'depends'       => 'generate_invoice:1',
                'validation'    => 'required_if:generate_invoice,1',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'order_status',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => Order::STATUS_PENDING,
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => Order::STATUS_PENDING_PAYMENT,
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => Order::STATUS_PROCESSING,
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.payment_methods.moneytransfer',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.money-transfer-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.logo',
                'type'          => 'image',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'generate_invoice',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.generate-invoice',
                'type'          => 'boolean',
                'default_value' => false,
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'       => 'invoice_status',
                'depends'    => 'generate_invoice:1',
                'validation' => 'required_if:generate_invoice,1',
                'title'      => 'admin::app.configuration.index.sales.payment-methods.set-invoice-status',
                'type'       => 'select',
                'options'    => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.paid',
                        'value' => 'paid',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'order_status',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.set-order-status',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending',
                        'value' => 'pending',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.pending-payment',
                        'value' => 'pending_payment',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.payment-methods.processing',
                        'value' => 'processing',
                    ],
                ],
                'info'          => 'admin::app.configuration.index.sales.payment-methods.generate-invoice-applicable',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'mailing_address',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.mailing-address',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.payment_methods.paypal_standard',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.paypal-standard',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.paypal-standard-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.logo',
                'type'          => 'image',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'business_account',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.business-account',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ],  [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.payment_methods.paypal_smart_button',
        'name'   => 'admin::app.configuration.index.sales.payment-methods.paypal-smart-button',
        'info'   => 'admin::app.configuration.index.sales.payment-methods.paypal-smart-button-info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'image',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.logo',
                'type'          => 'image',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.logo-information',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ], [
                'name'          => 'client_id',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.client-id',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.client-id-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'client_secret',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.client-secret',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.client-secret-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'accepted_currencies',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.accepted-currencies',
                'info'          => 'admin::app.configuration.index.sales.payment-methods.accepted-currencies-info',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'          => 'sandbox',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.sandbox',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false,
            ], [
                'name'    => 'sort',
                'title'   => 'admin::app.configuration.index.sales.payment-methods.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ],
        ],
    ], [
        'key'  => 'sales.order_settings',
        'name' => 'admin::app.configuration.index.sales.order-settings.title',
        'info' => 'admin::app.configuration.index.sales.order-settings.info',
        'icon' => 'settings/order.svg',
        'sort' => 4,
    ], [
        'key'    => 'sales.order_settings.order_number',
        'name'   => 'admin::app.configuration.index.sales.order-settings.order-number.title',
        'info'   => 'admin::app.configuration.index.sales.order-settings.order-number.info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'order_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
            ], [
                'name'          => 'order_number_length',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ], [
                'name'          => 'order_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
            ], [
                'name'          => 'order_number_generator',
                'title'         => 'admin::app.configuration.index.sales.order-settings.order-number.generator',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.order_settings.minimum_order',
        'name'   => 'admin::app.configuration.index.sales.order-settings.minimum-order.title',
        'info'   => 'admin::app.configuration.index.sales.order-settings.minimum-order.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'enable',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.enable',
                'type'          => 'boolean',
            ], [
                'name'          => 'minimum_order_amount',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.minimum-order-amount',
                'type'          => 'number',
                'validation'    => 'required_if:enable,1|numeric',
                'depends'       => 'enable:1',
                'channel_based' => true,
            ], [
                'name'          => 'include_discount_amount',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.include-discount-amount',
                'type'          => 'boolean',
                'depends'       => 'enable:1',
            ], [
                'name'          => 'include_tax_to_amount',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.include-tax-amount',
                'type'          => 'boolean',
                'depends'       => 'enable:1',
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.order-settings.minimum-order.description',
                'type'          => 'textarea',
                'depends'       => 'enable:1',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.order_settings.reorder',
        'name'   => 'admin::app.configuration.index.sales.order-settings.reorder.title',
        'info'   => 'admin::app.configuration.index.sales.order-settings.reorder.info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'admin',
                'title'   => 'admin::app.configuration.index.sales.order-settings.reorder.admin-reorder',
                'info'    => 'admin::app.configuration.index.sales.order-settings.reorder.admin-reorder-info',
                'type'    => 'boolean',
                'default' => true,
            ], [
                'name'    => 'shop',
                'title'   => 'admin::app.configuration.index.sales.order-settings.reorder.shop-reorder',
                'info'    => 'admin::app.configuration.index.sales.order-settings.reorder.shop-reorder-info',
                'type'    => 'boolean',
                'default' => true,
            ],
        ],
    ], [
        'key'  => 'sales.invoice_settings',
        'name' => 'admin::app.configuration.index.sales.invoice-settings.title',
        'info' => 'admin::app.configuration.index.sales.invoice-settings.info',
        'icon' => 'settings/invoice.svg',
        'sort' => 5,
    ], [
        'key'    => 'sales.invoice_settings.invoice_number',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.info',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'invoice_number_prefix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.prefix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_length',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.length',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_suffix',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.suffix',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ], [
                'name'          => 'invoice_number_generator_class',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-number.generator',
                'type'          => 'text',
                'validation'    => false,
                'channel_based' => true,
                'locale_based'  => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.payment_terms',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'due_duration',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.payment-terms.due-duration',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.pdf_print_outs',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'invoice_id',
                'title'   => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.invoice-id-title',
                'info'    => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.invoice-id-info',
                'type'    => 'boolean',
                'default' => true,
            ], [
                'name'    => 'order_id',
                'title'   => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.order-id-title',
                'info'    => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.order-id-info',
                'type'    => 'boolean',
                'default' => true,
            ], [
                'name'          => 'logo',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.logo',
                'info'          => 'admin::app.configuration.index.sales.invoice-settings.pdf-print-outs.logo-info',
                'type'          => 'image',
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
                'channel_based' => true,
            ],
        ],
    ], [
        'key'    => 'sales.invoice_settings.invoice_reminders',
        'name'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.title',
        'info'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.info',
        'sort'   => 3,
        'fields' => [
            [
                'name'          => 'reminders_limit',
                'title'         => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.maximum-limit-of-reminders',
                'type'          => 'text',
                'validation'    => 'numeric',
                'channel_based' => true,
            ], [
                'name'    => 'interval_between_reminders',
                'title'   => 'admin::app.configuration.index.sales.invoice-settings.invoice-reminders.interval-between-reminders',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1 day',
                        'value' => 'P1D',
                    ], [
                        'title' => '2 days',
                        'value' => 'P2D',
                    ], [
                        'title' => '3 days',
                        'value' => 'P3D',
                    ], [
                        'title' => '4 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '5 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '6 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '7 days',
                        'value' => 'P4D',
                    ], [
                        'title' => '2 weeks',
                        'value' => 'P2W',
                    ], [
                        'title' => '3 weeks',
                        'value' => 'P3W',
                    ], [
                        'title' => '4 weeks',
                        'value' => 'P4W',
                    ],
                ],
            ],
        ],
    ], [
        'key'  => 'sales.taxes',
        'name' => 'admin::app.configuration.index.sales.taxes.title',
        'info' => 'admin::app.configuration.index.sales.taxes.title-info',
        'icon' => 'settings/tax.svg',
        'sort' => 6,
    ], [
        'key'    => 'sales.taxes.categories',
        'name'   => 'admin::app.configuration.index.sales.taxes.categories.title',
        'info'   => 'admin::app.configuration.index.sales.taxes.categories.title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'shipping',
                'title'   => 'admin::app.configuration.index.sales.taxes.categories.shipping',
                'type'    => 'select',
                'default' => 0,
                'options' => 'Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
            ], [
                'name'    => 'product',
                'title'   => 'admin::app.configuration.index.sales.taxes.categories.product',
                'type'    => 'select',
                'default' => 0,
                'options' => 'Webkul\Tax\Repositories\TaxCategoryRepository@getConfigOptions',
            ],
        ],
    ], [
        'key'    => 'sales.taxes.calculation',
        'name'   => 'admin::app.configuration.index.sales.taxes.calculation.title',
        'info'   => 'admin::app.configuration.index.sales.taxes.calculation.title-info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'based_on',
                'title'   => 'admin::app.configuration.index.sales.taxes.calculation.based-on',
                'type'    => 'select',
                'default' => 'shipping_address',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.shipping-address',
                        'value' => 'shipping_address',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.billing-address',
                        'value' => 'billing_address',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.shipping-origin',
                        'value' => 'shipping_origin',
                    ],
                ],
            ], [
                'name'    => 'product_prices',
                'title'   => 'admin::app.configuration.index.sales.taxes.calculation.product-prices',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.including-tax',
                        'value' => 'including_tax',
                    ],
                ],
            ], [
                'name'    => 'shipping_prices',
                'title'   => 'admin::app.configuration.index.sales.taxes.calculation.shipping-prices',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.calculation.including-tax',
                        'value' => 'including_tax',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.taxes.default_destination_calculation',
        'name'   => 'admin::app.configuration.index.sales.taxes.default-destination-calculation.title',
        'info'   => 'admin::app.configuration.index.sales.taxes.default-destination-calculation.title-info',
        'sort'   => 3,
        'fields' => [
            [
                'name'    => 'country',
                'title'   => 'admin::app.configuration.index.sales.taxes.default-destination-calculation.default-country',
                'type'    => 'country',
                'default' => '',
            ], [
                'name'    => 'state',
                'title'   => 'admin::app.configuration.index.sales.taxes.default-destination-calculation.default-state',
                'type'    => 'state',
                'default' => '',
            ], [
                'name'    => 'post_code',
                'title'   => 'admin::app.configuration.index.sales.taxes.default-destination-calculation.default-post-code',
                'type'    => 'text',
                'default' => '',
            ],
        ],
    ], [
        'key'    => 'sales.taxes.shopping_cart',
        'name'   => 'admin::app.configuration.index.sales.taxes.shopping-cart.title',
        'info'   => 'admin::app.configuration.index.sales.taxes.shopping-cart.title-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'    => 'display_prices',
                'title'   => 'admin::app.configuration.index.sales.taxes.shopping-cart.display-prices',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name'    => 'display_subtotal',
                'title'   => 'admin::app.configuration.index.sales.taxes.shopping-cart.display-subtotal',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name'    => 'display_shipping_amount',
                'title'   => 'admin::app.configuration.index.sales.taxes.shopping-cart.display-shipping-amount',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.shopping-cart.both',
                        'value' => 'both',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.taxes.sales',
        'name'   => 'admin::app.configuration.index.sales.taxes.sales.title',
        'info'   => 'admin::app.configuration.index.sales.taxes.sales.title-info',
        'sort'   => 4,
        'fields' => [
            [
                'name'    => 'display_prices',
                'title'   => 'admin::app.configuration.index.sales.taxes.sales.display-prices',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name'    => 'display_subtotal',
                'title'   => 'admin::app.configuration.index.sales.taxes.sales.display-subtotal',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ], [
                'name'    => 'display_shipping_amount',
                'title'   => 'admin::app.configuration.index.sales.taxes.sales.display-shipping-amount',
                'type'    => 'select',
                'default' => 'excluding_tax',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.excluding-tax',
                        'value' => 'excluding_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.including-tax',
                        'value' => 'including_tax',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.taxes.sales.both',
                        'value' => 'both',
                    ],
                ],
            ],
        ],
    ], [
        'key'  => 'sales.checkout',
        'name' => 'admin::app.configuration.index.sales.checkout.title',
        'info' => 'admin::app.configuration.index.sales.checkout.info',
        'icon' => 'settings/checkout.svg',
        'sort' => 7,
    ], [
        'key'    => 'sales.checkout.shopping_cart',
        'name'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.title',
        'info'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.info',
        'sort'   => 1,
        'fields' => [
            [
                'name'    => 'allow_guest_checkout',
                'title'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.guest-checkout',
                'info'    => 'admin::app.configuration.index.sales.checkout.shopping-cart.guest-checkout-info',
                'type'    => 'boolean',
                'default' => 1,
            ], [
                'name'    => 'cart_page',
                'title'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.cart-page',
                'info'    => 'admin::app.configuration.index.sales.checkout.shopping-cart.cart-page-info',
                'type'    => 'boolean',
                'default' => 2,
            ], [
                'name'    => 'cross_sell',
                'title'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.cross-sell',
                'info'    => 'admin::app.configuration.index.sales.checkout.shopping-cart.cross-sell-info',
                'type'    => 'boolean',
                'default' => 3,
            ], [
                'name'    => 'estimate_shipping',
                'title'   => 'admin::app.configuration.index.sales.checkout.shopping-cart.estimate-shipping',
                'info'    => 'admin::app.configuration.index.sales.checkout.shopping-cart.estimate-shipping-info',
                'type'    => 'boolean',
                'default' => 4,
            ],
        ],
    ], [
        'key'    => 'sales.checkout.my_cart',
        'name'   => 'admin::app.configuration.index.sales.checkout.my-cart.title',
        'info'   => 'admin::app.configuration.index.sales.checkout.my-cart.info',
        'sort'   => 2,
        'fields' => [
            [
                'name'    => 'summary',
                'title'   => 'admin::app.configuration.index.sales.checkout.my-cart.summary',
                'type'    => 'select',
                'default' => 'display_number_of_items_in_cart',
                'options' => [
                    [
                        'title' => 'admin::app.configuration.index.sales.checkout.my-cart.display-item-quantities',
                        'value' => 'display_item_quantity',
                    ], [
                        'title' => 'admin::app.configuration.index.sales.checkout.my-cart.display-number-in-cart',
                        'value' => 'display_number_of_items_in_cart',
                    ],
                ],
            ],
        ],
    ], [
        'key'    => 'sales.checkout.mini_cart',
        'name'   => 'admin::app.configuration.index.sales.checkout.mini-cart.title',
        'info'   => 'admin::app.configuration.index.sales.checkout.mini-cart.info',
        'sort'   => 3,
        'fields' => [
            [
                'name'    => 'display_mini_cart',
                'title'   => 'admin::app.configuration.index.sales.checkout.mini-cart.display-mini-cart',
                'type'    => 'boolean',
                'default' => 1,
            ], [
                'name'    => 'offer_info',
                'title'   => 'admin::app.configuration.index.sales.checkout.mini-cart.mini-cart-offer-info',
                'type'    => 'text',
                'default' => 'Get Up To 30% OFF on your 1st order',
            ],
        ],
    ],
];
