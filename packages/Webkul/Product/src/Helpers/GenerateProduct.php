<?php

namespace Webkul\Product\Helpers;

use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Illuminate\Support\Str;

class GenerateProduct
{
    /**
     * Product Repository instance
     */
    protected $product;

    /**
     * AttributeFamily Repository instance
     */
    protected $attributeFamily;

    /**
     * Product Attribute Types
     */
    protected $types;

    public function __construct(Product $product, AttributeFamily $attributeFamily)
    {
        $this->product = $product;

        $this->types = [
            'text', 'textarea', 'boolean', 'select', 'multiselect', 'datetime', 'date', 'price', 'image', 'file', 'checkbox'
        ];

        $this->attributeFamily = $attributeFamily;
    }

    public function create()
    {
        $attributes = $this->getDefaultFamilyAttributes();

        $attributeFamily = $this->attributeFamily->findWhere([
            'code' => 'default'
        ]);

        $sku = Str::random(10);
        $data['sku'] = strtolower($sku);
        $data['attribute_family_id'] = $attributeFamily->first()->id;
        $data['type'] = 'simple';

        $product = $this->product->create($data);

        unset($data);

        $faker = \Faker\Factory::create();
        $date = date('Y-m-d');
        $date = \Carbon\Carbon::parse($date);
        $specialFrom = $date->toDateString();
        $specialTo = $date->addDays(7)->toDateString();

        foreach ($attributes as $attribute) {
            if ($attribute->type == 'text') {
                if ($attribute->code == 'width' || $attribute->code == 'height' || $attribute->code == 'depth' || $attribute->code == 'weight') {
                    $data[$attribute->code] = $faker->randomNumber(3);
                } else if ($attribute->code == 'url_key') {
                    $data[$attribute->code] = strtolower($sku);
                } else if ($attribute->code != 'sku') {
                    $data[$attribute->code] = $faker->name;
                } else {
                    $data[$attribute->code] = $sku;
                }
            } else if ($attribute->type == 'textarea') {
                $data[$attribute->code] = $faker->text;

                if ($attribute->code == 'description' || $attribute->code == 'short_description') {
                    $data[$attribute->code] = '<p>'. $data[$attribute->code] . '</p>';
                }
            } else if ($attribute->type == 'boolean') {
                $data[$attribute->code] = $faker->boolean;
            } else if ($attribute->type == 'price') {
                $data[$attribute->code] = $faker->randomNumber(2);
            } else if ($attribute->type == 'datetime') {
                $data[$attribute->code] = $date->toDateTimeString();
            } else if ($attribute->type == 'date') {
                if ($attribute->code == 'special_price_from') {
                    $data[$attribute->code] = $specialFrom;
                } else if ($attribute->code == 'special_price_to') {
                    $data[$attribute->code] = $specialTo;
                } else {
                    $data[$attribute->code] = $date->toDateString();
                }
            } else if ($attribute->code != 'tax_category_id' && ($attribute->type == 'select' || $attribute->type == 'multiselect')) {
                $options = $attribute->options;

                if ($attribute->type == 'select') {
                    if ($options->count()) {
                        $option = $options->first()->id;

                        $data[$attribute->code] = $option;
                    } else {
                        $data[$attribute->code] = "";
                    }
                } else if ($attribute->type == 'multiselect') {
                    if ($options->count()) {
                        $option = $options->first()->id;

                        $optionArray = [];

                        array_push($optionArray, $option);

                        $data[$attribute->code] = $optionArray;
                    } else {
                        $data[$attribute->code] = "";
                    }
                } else {
                    $data[$attribute->code] = "";
                }
            } else if ($attribute->code == 'checkbox') {
                $options = $attribute->options;

                 if ($options->count()) {
                    $option = $options->first()->id;

                    $optionArray = [];

                    array_push($optionArray, $option);

                    $data[$attribute->code] = $optionArray;
                } else {
                    $data[$attribute->code] = "";
                }
            }
        }

        $channel = core()->getCurrentChannel();

        $data['locale'] = core()->getCurrentLocale()->code;

        $data['channel'] = $channel->code;

        $data['channels'] = [
            0 => $channel->id
        ];

        $inventorySource = $channel->inventory_sources[0];

        $data['inventories'] = [
            $inventorySource->id => 10
        ];

        $data['categories'] = [
            0 => $channel->root_category->id
        ];

        $updated = $this->product->update($data, $product->id);

        return $updated;
    }

    public function getDefaultFamilyAttributes()
    {
        $attributeFamily = $this->attributeFamily->findWhere([
            'code' => 'default'
        ]);

        $attributes = collect();

        if ($attributeFamily->count()) {
            $attributeGroups = $attributeFamily->first()->attribute_groups;

            foreach ($attributeGroups as $attributeGroup) {
                foreach ($attributeGroup->custom_attributes as $customAttribute) {
                    $attributes->push($customAttribute);
                }
            }
        }

        return $attributes;
    }
}