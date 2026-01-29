<?php

namespace Webkul\Admin\Validations;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Product\Repositories\ProductRepository;

class ProductCategoryUniqueSlug implements ValidationRule
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
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array($value, $this->reservedSlugs)) {
            $fail('admin::app.validations.slug-reserved')->translate();

            return;
        }

        if (! $this->isSlugUnique($value)) {
            $fail('admin::app.validations.slug-being-used')->translate();
        }
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
