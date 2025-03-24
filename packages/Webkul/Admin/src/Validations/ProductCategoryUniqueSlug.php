<?php

namespace Webkul\Admin\Validations;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Product\Repositories\ProductRepository;

class ProductCategoryUniqueSlug implements Rule
{
    /**
     * Reserved slugs.
     *
     * @var array
     */
    protected $reservedSlugs = [
        'categories',
    ];

    /**
     * Is slug reserved.
     *
     * @var bool
     */
    protected $isSlugReserved = false;

    /**
     * Constructor.
     *
     * @param  string  $tableName
     * @param  string  $id
     */
    public function __construct(
        protected $tableName = null,
        protected $id = null
    ) {}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (in_array($value, $this->reservedSlugs)) {
            return ! ($this->isSlugReserved = true);
        }

        return $this->isSlugUnique($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->isSlugReserved) {
            return trans('admin::app.validations.slug-reserved');
        }

        return trans('admin::app.validations.slug-being-used');
    }

    /**
     * Checks slug is unique or not.
     *
     * @param  string  $slug
     * @return bool
     */
    protected function isSlugUnique($slug)
    {
        return ! $this->isSlugExistsInCategories($slug) && ! $this->isSlugExistsInProducts($slug);
    }

    /**
     * Is slug is exists in categories.
     *
     * @param  string  $slug
     * @return bool
     */
    protected function isSlugExistsInCategories($slug)
    {
        if (
            $this->tableName
            && $this->id
            && $this->tableName === 'category_translations'
        ) {
            return CategoryTranslationProxy::modelClass()::where('category_id', '<>', $this->id)
                ->where('slug', $slug)
                ->limit(1)
                ->select(DB::raw(1))
                ->exists();
        }

        return CategoryTranslationProxy::modelClass()::where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();
    }

    /**
     * Is slug is exists in products.
     *
     * @param  string  $slug
     * @return bool
     */
    protected function isSlugExistsInProducts($slug)
    {
        if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
            $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
        }

        $product = app(ProductRepository::class)
            ->setSearchEngine($searchEngine ?? 'database')
            ->findBySlug($slug);

        if (
            $product
            && $this->tableName
            && $this->id
            && $this->tableName === 'products'
            && $this->id == $product->id
        ) {
            $product = null;
        }

        return (bool) $product;
    }
}
