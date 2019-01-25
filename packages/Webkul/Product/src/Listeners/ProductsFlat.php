<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Webkul\Product\Repositories\ProductFlatRepository as ProductFlat;
use Webkul\Product\Repositories\ProductAttributeValueRepository as ProductAttributeValue;
/**
 * Products Flat Event handler
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductsFlat
{
    /**
     * ProductFlat Repository Object
     *
     * @vatr array
     */
    protected $productFlat;

    protected $productAttributeValue;

    protected $attribute;

    public function __construct(ProductFlat $productFlat, ProductAttributeValue $productAttributeValue) {
        $this->productAttributeValue = $productAttributeValue;

        $this->productFlat = $productFlat;
    }

    /**
     * After the attribute is created
     *
     * @return void
     */
    public function afterAttributeCreated($attribute)
    {
        if(!$attribute->is_user_defined) {
            return false;
        }

        $attributeType = $attribute->type;
        $attributeCode = $attribute->code;

        if ($attributeType == 'text' || $attributeType == 'textarea') {
            $columnType = 'text';
        } else if ($attributeType == 'price') {
            $columnType = 'decimal';
        } else if ($attributeType == 'boolean') {
            $columnType = 'boolean';
        } else if ($attributeType == 'select' || $attributeType == 'multiselect') {
            if ($attributeType == 'multiselect') {
                $columnType = 'text';
            } else {
                $columnType = 'integer';
            }
        } else if ($attributeType == 'datetime') {
            $columnType = 'dateTime';
        } else if ($attributeType == 'date') {
            $columnType = 'date';
        } else {
            return false;
        }

        if (Schema::hasTable('product_flat')) {
            if (!Schema::hasColumn('product_flat', strtolower($attribute->code))) {
                Schema::table('product_flat', function (Blueprint $table) use($columnType, $attributeCode, $attributeType) {
                    $table->{$columnType}(strtolower($attributeCode))->nullable();

                    if($attributeType == 'select' || $attributeType == 'multiselect') {
                        $table->string(strtolower($attributeCode).'_label')->nullable();
                    }
                });

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * After the attribute is updated
     *
     * @return void
     */
    public function afterAttributeUpdated($attribute)
    {
        return true;
    }

    public function afterAttributeDeleted($attribute)
    {
        if (Schema::hasTable('product_flat')) {
            if (Schema::hasColumn('product_flat', strtolower($attribute->code))) {
                Schema::table('product_flat', function (Blueprint $table) use($attribute){
                    $table->dropColumn(strtolower($attribute->code));

                    if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                        $table->dropColumn(strtolower($attribute->code).'_label');
                    }
                });

                return true;
            } else {
                return false;
            }
        }
    }

    public function afterProductCreatedOrUpdated($ProductFlat) {
        $product = $ProductFlat;
        $productAttributes = $product->attribute_family->custom_attributes;
        $allLocales = core()->getAllLocales();
        $productsFlat = array();
        $channelLocaleMap = array();
        $nonDependentAttributes = array();
        $localeDependentAttributes = array();
        $channelDependentAttributes = array();
        $channelLocaleDependentAttributes = array();

        foreach($productAttributes as $key => $productAttribute) {
            if($productAttribute->value_per_channel) {
                if($productAttribute->value_per_locale) {
                    array_push($channelLocaleDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
                } else {
                    array_push($channelDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
                }
            } else if($productAttribute->value_per_locale && !$productAttribute->value_per_channel) {
                array_push($localeDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
            } else {
                array_push($nonDependentAttributes, ['id' => $productAttribute->id, 'code' => $productAttribute->code]);
            }
        }

        foreach(core()->getAllChannels() as $channel) {
            $dummy = [
                'product_id' => $product->id,
                'channel' => $channel->code,
                'locale' => null,
                'data' => $channelDependentAttributes
            ];

            array_push($channelLocaleMap, $dummy);

            $dummy = [];

            foreach($channel->locales as $locale) {
                $dummy = [
                    'product_id' => $product->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code,
                    'data' => $channelLocaleDependentAttributes
                ];

                array_push($channelLocaleMap, $dummy);

                $dummy = [];
            }
        }

        $dummy = [
            'product_id' => $product->id,
            'channel' => null,
            'locale' => null,
            'data' => $nonDependentAttributes
        ];

        array_push($channelLocaleMap, $dummy);

        $dummy = [];

        foreach($allLocales as $key => $allLocale) {
            $dummy = [
                'product_id' => $product->id,
                'channel' => null,
                'locale' => $allLocale->code,
                'data' => $localeDependentAttributes
            ];

            array_push($channelLocaleMap, $dummy);

            $dummy = [];
        }

        $productFlatObjects = $channelLocaleMap;
        $keyOfNonDependentAttributes = null;

        foreach($productAttributes as $productAttribute) {
            foreach($productFlatObjects as $flatKey => $productFlatObject) {
                if($productFlatObject['channel'] == null && $productFlatObject['locale'] == null) {
                    $keyOfNonDependentAttributes = $flatKey;
                }

                foreach($productFlatObject['data'] as $key => $value) {
                    if($productAttribute->code == $value['code']) {
                        $valueOf = $this->productAttributeValue->findOneWhere([
                            'product_id' => $product->id,
                            'channel' => $productFlatObject['channel'],
                            'locale' => $productFlatObject['locale'],
                            'attribute_id' => $productAttribute->id
                        ]);

                        if($valueOf != null) {
                            $productAttributeColumn = $this->productAttributeValue->model()::$attributeTypeFields[$productAttribute->type];

                            $valueOf = $valueOf->{$productAttributeColumn};

                            $productFlatObjects[$flatKey][$productAttribute->code] = $valueOf;
                        } else {
                            $productFlatObjects[$flatKey][$productAttribute->code] = 'null';
                        }
                    }
                }
            }
        }

        $nonDependentAttributes = $productFlatObjects[$keyOfNonDependentAttributes];

        array_forget($nonDependentAttributes, ['product_id', 'channel', 'locale', 'data', 'visible_individually', 'width', 'height', 'depth', 'tax_category_id']);

        unset($productFlatObjects[$keyOfNonDependentAttributes]);

        $productFlatEntryObject = array();

        $tempFlatObject = array();

        foreach($productFlatObjects as $flatKey => $productFlatObject) {
            unset($productFlatObject['data']);

            if(isset($productFlatObject['short_description'])) {
                $productFlatObject['description'] = $productFlatObject['short_description'];

                unset($productFlatObject['short_description']);
            }

            if(isset($productFlatObject['meta_title'])) {
                unset($productFlatObject['meta_title']);
                unset($productFlatObject['meta_description']);
                unset($productFlatObject['meta_keywords']);
            }

            $tempFlatObject = array_merge($productFlatObject, $nonDependentAttributes);

            $tempFlatObject = core()->convertEmptyStringsToNull($tempFlatObject);

            $exists = $this->productFlat->findWhere([
                'product_id' => $product->id,
                'channel' => $tempFlatObject['channel'],
                'locale' => $tempFlatObject['locale']
            ]);

            if($exists->count() == 0) {
                $result = $this->productFlat->create($tempFlatObject);
            } else {
                $result = $exists->first();

                $result->update($tempFlatObject);
            }

            unset($tempFlatObject);
        }

        return 'true';
    }
}