<?php

namespace Webkul\Category\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Category\Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\ProductProxy;

/**
 * Class Category
 *
 * @package Webkul\Category\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class Category extends TranslatableModel implements CategoryContract
{

    use NodeTrait;
    use HasFactory;

    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    protected $with = ['translations'];

    /**
     * Get image url for the category image.
     */
    public function image_url()
    {
        if (!$this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * The filterable attributes that belong to the category.
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')
                    ->with([
                        'options' => function ($query) {
                            $query->orderBy('sort_order');
                        },
                    ]);
    }

    /**
     * Getting the root category of a category
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function getRootCategory()
    {
        return self::query()
                   ->where([
                       [
                           'parent_id',
                           '=',
                           null,
                       ],
                       [
                           '_lft',
                           '<=',
                           $this->_lft,
                       ],
                       [
                           '_rgt',
                           '>=',
                           $this->_rgt,
                       ],
                   ])
                   ->first();
    }

    /**
     * Returns all categories within the category's path
     *
     * @return Category[]
     */
    public function getPathCategories(): array
    {
        $category = $this->findInTree();

        $categories = [$category];

        while (isset($category->parent)) {
            $category     = $category->parent;
            $categories[] = $category;
        }

        return array_reverse($categories);
    }

    /**
     * Finds and returns the category within a nested category tree
     * will search in root category by default
     * is used to minimize the numbers of sql queries for it only uses the already cached tree
     *
     * @param  Category[]  $categoryTree
     *
     * @return Category
     */
    public function findInTree($categoryTree = null): Category
    {
        if (!$categoryTree) {
            $categoryTree = app(CategoryRepository::class)->getVisibleCategoryTree($this->getRootCategory()->id);
        }

        $category = $categoryTree->first();

        if (!$category) {
            throw new NotFoundHttpException('category not found in tree');
        }

        if ($category->id === $this->id) {
            return $category;
        }

        return $this->findInTree($category->children);
    }

    /**
     * The products that belong to the category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_categories');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return CategoryFactory
     */
    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

}