<?php

namespace Webkul\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;

class AttributeFamilyTableSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $rawData = [
        [
            "code" => "default",
            "name" => "Default",
            "is_user_defined" => 0,
            'attribute_groups' => [
                [
                    "name" => "General",
                    "is_user_defined" => 0,
                    "position" => 1,
                    "attributes" => [
                        [
                            'code' => 'name',
                            'position' => 1
                        ], [
                            'code' => 'url_key',
                            'position' => 2
                        ], [
                            'code' => 'new_from',
                            'position' => 3
                        ], [
                            'code' => 'new_to',
                            'position' => 4
                        ], [
                            'code' => 'status',
                            'position' => 5
                        ]
                    ]
                ], [
                    "name" => "Description",
                    "is_user_defined" => 0,
                    "position" => 2,
                    "attributes" => [
                        [
                            'code' => 'short_description',
                            'position' => 1
                        ], [
                            'code' => 'description',
                            'position' => 2
                        ]
                    ]
                ], [
                    "name" => "Meta Description",
                    "is_user_defined" => 0,
                    "position" => 3,
                    "attributes" =>  [
                        [
                            'code' => 'meta_title',
                            'position' => 1
                        ], [
                            'code' => 'meta_keywords',
                            'position' => 2
                        ], [
                            'code' => 'meta_description',
                            'position' => 3
                        ]
                    ]
                ], [
                    "name" => "Price",
                    "is_user_defined" => 0,
                    "position" => 4,
                    "attributes" => [
                        [
                            'code' => 'price',
                            'position' => 1
                        ], [
                            'code' => 'cost',
                            'position' => 2
                        ], [
                            'code' => 'special_price',
                            'position' => 3
                        ], [
                            'code' => 'special_price_from',
                            'position' => 4
                        ], [
                            'code' => 'special_price_to',
                            'position' => 5
                        ]
                    ]
                ], [
                    "name" => "Shipping",
                    "is_user_defined" => 0,
                    "position" => 5,
                    "attributes" => [
                        [
                            'code' => 'width',
                            'position' => 1
                        ], [
                            'code' => 'height',
                            'position' => 2
                        ], [
                            'code' => 'depth',
                            'position' => 3
                        ], [
                            'code' => 'weight',
                            'position' => 4
                        ]
                    ]
                ]
            ]
        ]
    ];

    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @return void
     */
    public function __construct(AttributeFamilyRepository $attributeFamily)
    {
        $this->attributeFamily = $attributeFamily;
    }
    
    public function run()
    {
        foreach($this->rawData as $row) {
            $this->attributeFamily->create($row);
        }
    }
}