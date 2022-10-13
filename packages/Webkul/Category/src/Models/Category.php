<?php

namespace Webkul\Category\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\NodeTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Category\Database\Factories\CategoryFactory;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Models\ProductProxy;

/**
 * Category class.
 *
 * @package Webkul\Category\Models
 *
 * @property-read string (`$url_path` maintained by database triggers.)
 */
class Category extends TranslatableModel implements CategoryContract
{
    use NodeTrait, HasFactory;

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['image_url', 'category_icon_url'];

    /**
     * The products that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_categories');
    }

    /**
     * The filterable attributes that belong to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')
            ->with([
                'options' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'translations',
                'options.translations',
            ]);
    }

    /**
     * Finds and returns the category within a nested category tree.
     *
     * Will search in root category by default.
     *
     * Is used to minimize the numbers of sql queries for it only uses the already cached tree.
     *
     * @param  \Webkul\Velocity\Contracts\Category[]  $categoryTree
     * @return \Webkul\Velocity\Contracts\Category
     */
    public function findInTree($categoryTree = null): Category
    {
        if (! $categoryTree) {
            $categoryTree = app(CategoryRepository::class)->getVisibleCategoryTree($this->getRootCategory()->id);
        }

        $category = $categoryTree->first();

        if (! $category) {
            throw new NotFoundHttpException('category not found in tree');
        }

        if ($category->id === $this->id) {
            return $category;
        }

        return $this->findInTree($category->children);
    }

    /**
     * Getting the root category of a category.
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
                ], [
                    '_lft',
                    '<=',
                    $this->_lft,
                ], [
                    '_rgt',
                    '>=',
                    $this->_rgt,
                ],
            ])
            ->first();
    }

    /**
     * Returns all categories within the category's path.
     *
     * @return \Webkul\Velocity\Contracts\Category[]
     */
    public function getPathCategories(): array
    {
        $category = $this->findInTree();

        $categories = [$category];

        while (isset($category->parent)) {
            $category = $category->parent;
            
            $categories[] = $category;
        }

        return array_reverse($categories);
    }

    /**
     * Get image url for the category image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get category icon url for the category icon image.
     *
     * @return string
     */
    public function getCategoryIconUrlAttribute()
    {
        if (! $this->category_icon_path) {
            return;
        }

        return Storage::url($this->category_icon_path);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return CategoryFactory::new ();
    }
}
