<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class Attribute
{
    /**
     * @var array
     */
    public $attributeTypeFields = [
        'text'        => 'text',
        'textarea'    => 'text',
        'price'       => 'float',
        'boolean'     => 'boolean',
        'select'      => 'integer',
        'multiselect' => 'text',
        'datetime'    => 'datetime',
        'date'        => 'date',
        'file'        => 'text',
        'image'       => 'text',
        'checkbox'    => 'text',
    ];

    /**
     * @var array
     */
    protected $flatColumns = [];

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected ProductFlatRepository $productFlatRepository
    )
    {
        $this->flatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * After the attribute is created
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @return void
     */
    public function updateCreateFlatColumn($attribute)
    {
        if (! $attribute->is_user_defined) {
            return;
        }

        if (! $attribute->use_in_flat) {
            $this->removeFlatColumn($attribute->id);

            return;
        }

        if (in_array($attribute->code, $this->flatColumns)) {
            return;
        }

        Schema::table('product_flat', function (Blueprint $table) use($attribute) {
            $table->{$this->attributeTypeFields[$attribute->type]}($attribute->code)->nullable();

            if (in_array($attribute->type, ['select', 'multiselect'])) {
                $table->string($attribute->code . '_label')->nullable();
            }
        });
        
        $this->productFlatRepository->updateAttributeColumn($attribute, $this);
    }

    /**
     * After the attribute is deleted
     *
     * @param  int  $attributeId
     * @return void
     */
    public function removeFlatColumn($attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);
        
        if (! in_array(strtolower($attribute->code), $this->flatColumns)) {
            return;
        }

        Schema::table('product_flat', function (Blueprint $table) use($attribute) {
            $table->dropColumn($attribute->code);

            if (in_array($attribute->type, ['select', 'multiselect'])) {
                $table->dropColumn($attribute->code . '_label');
            }
        });
    }
}
