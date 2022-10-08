<?php

namespace Webkul\Product\Helpers\Indexers\Flat;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class Attribute
{
    /**
     * @var array
     */
    protected $flatColumns = [];

    /**
     * Create a new indexer instance.
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
    public function removeOrCreateColumn($attribute)
    {
        if (! $attribute->is_user_defined) {
            return;
        }

        if (! $attribute->use_in_flat) {
            $this->removeColumn($attribute->id);

            return;
        }

        if (in_array($attribute->code, $this->flatColumns)) {
            return;
        }

        Schema::table('product_flat', function (Blueprint $table) use($attribute) {
            $table->{Str::remove('_value', $attribute->column_name)}($attribute->code)->nullable();

            if (in_array($attribute->type, ['select', 'multiselect'])) {
                $table->string($attribute->code . '_label')->nullable();
            }
        });
        
        $this->productFlatRepository->updateAttributeColumn($attribute);
    }

    /**
     * After the attribute is deleted
     *
     * @param  int  $id
     * @return void
     */
    public function removeColumn($id)
    {
        $attribute = $this->attributeRepository->find($id);
        
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
