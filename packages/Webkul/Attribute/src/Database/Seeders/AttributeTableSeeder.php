<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Repositories\AttributeRepository;

class AttributeTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $rawData = [
        [
            'code' => 'sku',
            'admin_name' => 'SKU',
            // 'en' => [
            //     'name' => 'SKU'
            // ],
            'type' => 'text',
            'position' => 1,
            'is_unique' => 1,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'name',
            'admin_name' => 'Name',
            // 'en' => [
            //     'name' => 'Name'
            // ],
            'type' => 'text',
            'position' => 2,
            'is_required' => 1,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'url_key',
            'admin_name' => 'URL Key',
            // 'en' => [
            //     'name' => 'URL Key'
            // ],
            'type' => 'text',
            'position' => 3,
            'is_unique' => 1,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'new_from',
            'admin_name' => 'New From',
            // 'en' => [
            //     'name' => 'New From'
            // ],
            'type' => 'datetime',
            'position' => 4,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'new_to',
            'admin_name' => 'New To',
            // 'en' => [
            //     'name' => 'New To'
            // ],
            'type' => 'datetime',
            'position' => 5,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'visible_individually',
            'admin_name' => 'Visible Individually',
            // 'en' => [
            //     'name' => 'Visible Individually'
            // ],
            'type' => 'boolean',
            'position' => 6,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'status',
            'admin_name' => 'Status',
            // 'en' => [
            //     'name' => 'Status'
            // ],
            'type' => 'boolean',
            'position' => 7,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'short_description',
            'admin_name' => 'Short Description',
            // 'en' => [
            //     'name' => 'Short Description'
            // ],
            'type' => 'textarea',
            'position' => 8,
            'is_required' => 1,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'description',
            'admin_name' => 'Description',
            // 'en' => [
            //     'name' => 'Description'
            // ],
            'type' => 'textarea',
            'position' => 9,
            'is_required' => 1,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'price',
            'admin_name' => 'Price',
            // 'en' => [
            //     'name' => 'Price'
            // ],
            'type' => 'price',
            'position' => 10,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 1,
            'is_filterable' => 1,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'cost',
            'admin_name' => 'Cost',
            // 'en' => [
            //     'name' => 'Cost'
            // ],
            'type' => 'price',
            'position' => 11,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 1
        ], [
            'code' => 'special_price',
            'admin_name' => 'Special Price',
            // 'en' => [
            //     'name' => 'Special Price'
            // ],
            'type' => 'price',
            'position' => 12,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'special_price_from',
            'admin_name' => 'Special Price From',
            // 'en' => [
            //     'name' => 'Special Price From'
            // ],
            'type' => 'date',
            'position' => 13,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'special_price_to',
            'admin_name' => 'Special Price To',
            // 'en' => [
            //     'name' => 'Special Price To'
            // ],
            'type' => 'date',
            'position' => 14,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'meta_title',
            'admin_name' => 'Meta Title',
            // 'en' => [
            //     'name' => 'Meta Description'
            // ],
            'type' => 'textarea',
            'position' => 15,
            'is_required' => 0,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'meta_keywords',
            'admin_name' => 'Meta Keywords',
            // 'en' => [
            //     'name' => 'Meta Keywords'
            // ],
            'type' => 'textarea',
            'position' => 16,
            'is_required' => 0,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'meta_description',
            'admin_name' => 'Meta Description',
            // 'en' => [
            //     'name' => 'Meta Description'
            // ],
            'type' => 'textarea',
            'position' => 17,
            'is_required' => 0,
            'value_per_locale' => 1,
            'value_per_channel' => 1,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 1
        ], [
            'code' => 'width',
            'admin_name' => 'Width',
            // 'en' => [
            //     'name' => 'Width'
            // ],
            'type' => 'text',
            'validation' => 'numeric',
            'position' => 18,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 1
        ], [
            'code' => 'height',
            'admin_name' => 'Height',
            // 'en' => [
            //     'name' => 'Height'
            // ],
            'type' => 'text',
            'validation' => 'numeric',
            'position' => 19,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 1
        ], [
            'code' => 'depth',
            'admin_name' => 'Depth',
            // 'en' => [
            //     'name' => 'Depth'
            // ],
            'type' => 'text',
            'validation' => 'numeric',
            'position' => 20,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 1
        ], [
            'code' => 'weight',
            'admin_name' => 'Weight',
            // 'en' => [
            //     'name' => 'Weight'
            // ],
            'type' => 'text',
            'validation' => 'numeric',
            'position' => 21,
            'is_required' => 1,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 0,
            'is_configurable' => 0,
            'is_user_defined' => 0
        ], [
            'code' => 'color',
            'admin_name' => 'Color',
            // 'en' => [
            //     'name' => 'Color'
            // ],
            'type' => 'select',
            'position' => 22,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 1,
            'is_configurable' => 1,
            'is_user_defined' => 1,
            'options' => [
                [
                    'en' => [
                        'label' => 'Red'
                    ],
                    'sort_order' => 1
                ], [
                    'en' => [
                        'label' => 'Green'
                    ],
                    'sort_order' => 2
                ], [
                    'en' => [
                        'label' => 'Yellow'
                    ],
                    'sort_order' => 3
                ], [
                    'en' => [
                        'label' => 'Black'
                    ],
                    'sort_order' => 4
                ], [
                    'en' => [
                        'label' => 'White'
                    ],
                    'sort_order' => 5
                ]
            ]
        ], [
            'code' => 'size',
            'admin_name' => 'Size',
            // 'en' => [
            //     'name' => 'Size'
            // ],
            'type' => 'select',
            'position' => 23,
            'is_required' => 0,
            'value_per_locale' => 0,
            'value_per_channel' => 0,
            'is_filterable' => 1,
            'is_configurable' => 1,
            'is_user_defined' => 1,
            'options' => [
                [
                    'en' => [
                        'label' => 'S'
                    ],
                    'sort_order' => 1
                ], [
                    'en' => [
                        'label' => 'M'
                    ],
                    'sort_order' => 2
                ], [
                    'en' => [
                        'label' => 'L'
                    ],
                    'sort_order' => 3
                ], [
                    'en' => [
                        'label' => 'XL'
                    ],
                    'sort_order' => 4
                ]
            ]
        ]
    ];

    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @return void
     */
    public function __construct(AttributeRepository $attribute)
    {
        $this->attribute = $attribute;
    }

    public function run()
    {
        foreach($this->rawData as $row) {
            $this->attribute->create($row);
        }
    }
}