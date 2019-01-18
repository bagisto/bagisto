<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Products Flat Event handler
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductsFlat
{
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
        dd($attribute);
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
}