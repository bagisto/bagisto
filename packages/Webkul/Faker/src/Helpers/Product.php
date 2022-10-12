<?php

namespace Webkul\Faker\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Webkul\Product\Models\Product as ProductModel;

class Product
{
    /**
     * Containes product type faker classes.
     *
     * @var array
     */
    protected $types = [
        'simple',
        'virtual',
        // 'Configurable',
        // 'Downloadable',
        // 'Grouped',
        // 'Bundle',
        // 'Booking',
    ];

    /**
     * Create a records
     *
     * @params  integer  $count
     * @return void
     */
    public function create($count)
    {
        $product = ProductModel::factory()
            ->count($count)
            ->state(new Sequence(
                fn ($sequence) => [
                    'type' => Arr::random($this->types),
                ],
            ))
            ->hasInventories(1, [
                'inventory_source_id' => 1,
            ])
            ->create();
        
        
    }

    /**
     * Creates attribute values for the product
     *
     * @return void
     */
    public function createAttributeValues()
    {
    }
}