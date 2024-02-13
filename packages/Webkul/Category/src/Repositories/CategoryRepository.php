<?php

namespace Webkul\Category\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Webkul\Category\Contracts\Category;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Core\Eloquent\Repository;

class CategoryRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Category::class;
    }

    /**
     * Get categories.
     */
    public function getAll(array $params = []): LengthAwarePaginator
    {
        $queryBuilder = $this->query()
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id');

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'name':
                    $queryBuilder->where('category_translations.name', 'like', '%'.urldecode($value).'%');

                    break;
                case 'description':
                    $queryBuilder->where('category_translations.description', 'like', '%'.urldecode($value).'%');

                    break;
                case 'status':
                    $queryBuilder->where('categories.status', $value);

                    break;
                case 'only_children':
                    $queryBuilder->whereNotNull('categories.parent_id');

                    break;
                case 'parent_id':
                    $queryBuilder->where('categories.parent_id', $value);

                    break;
                case 'locale':
                    $queryBuilder->where('category_translations.locale', $value);

                    break;
            }
        }

        return $queryBuilder->paginate($params['limit'] ?? 10);
    }

    /**
     * Create category.
     */
    public function create(array $data): Category
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            foreach (core()->getAllLocales() as $locale) {
                foreach ($this->getModel()->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];

                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        $category = $this->getModel()->create($data);

        $this->uploadImages($data, $category);

        $this->uploadImages($data, $category, 'banner_path');

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        return $category;
    }

    /**
     * Update category.
     *
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Category\Contracts\Category
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $category = $this->find($id);

        $data = $this->setSameAttributeValueToAllLocale($data, 'slug');

        $category->update($data);

        $this->uploadImages($data, $category);

        $this->uploadImages($data, $category, 'banner_path');

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        return $category;
    }

    /**
     * Specify category tree.
     */
    public function getCategoryTree(?int $id = null): Collection
    {
        return $id
            ? $this->getModel()::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : $this->getModel()::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Specify category tree.
     */
    public function getCategoryTreeWithoutDescendant(?int $id = null): Collection
    {
        return $id
            ? $this->getModel()::orderBy('position', 'ASC')->where('id', '!=', $id)->whereNotDescendantOf($id)->get()->toTree()
            : $this->getModel()::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Get root categories.
     */
    public function getRootCategories(): Collection
    {
        return $this->getModel()->where('parent_id', null)->get();
    }

    /**
     * Get child categories.
     */
    public function getChildCategories($parentId): Collection
    {
        return $this->getModel()->where('parent_id', $parentId)->get();
    }

    /**
     * get visible category tree.
     */
    public function getVisibleCategoryTree(?int $id = null): Collection
    {
        return $id
            ? $this->getModel()::orderBy('position', 'ASC')->where('status', 1)->descendantsAndSelf($id)->toTree($id)
            : $this->getModel()::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();
    }

    /**
     * Checks slug is unique or not based on locale.
     */
    public function isSlugUnique(int $id, string $slug): bool
    {
        $exists = CategoryTranslationProxy::modelClass()::where('category_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return ! $exists;
    }

    /**
     * Retrieve category from slug.
     *
     * @return \Webkul\Category\Contracts\Category|void
     */
    public function findBySlug(string $slug)
    {
        if ($category = $this->getModel()->whereTranslation('slug', $slug)->first()) {
            return $category;
        }
    }

    /**
     * Retrieve category from slug.
     */
    public function findBySlugOrFail(string $slug): Category
    {
        return $this->getModel()->whereTranslation('slug', $slug)->firstOrFail();
    }

    /**
     * Upload category's images.
     */
    public function uploadImages(array $data, Category $category, string $type = 'logo_path'): void
    {
        if (empty($data[$type])) {
            if ($category->{$type}) {
                Storage::delete($category->{$type});
            }

            $category->{$type} = null;

            $category->save();

            return;
        }

        foreach ($data[$type] as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            if ($category->{$type}) {
                Storage::delete($category->{$type});
            }

            $image = (new ImageManager())->make($image)->encode('webp');

            $category->{$type} = 'category/'.$category->id.'/'.Str::uuid()->toString().'.webp';

            Storage::put($category->{$type}, $image);

            $category->save();
        }
    }

    /**
     * Get partials.
     *
     * @param  array|null  $columns
     * @return array
     */
    public function getPartial($columns = null)
    {
        $categories = $this->getModel()->all();

        $trimmed = [];

        foreach ($categories as $key => $category) {
            if (! empty($category->name)) {
                $trimmed[$key] = [
                    'id'   => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];
            }
        }

        return $trimmed;
    }

    /**
     * Set same value to all locales in category.
     *
     * To Do: Move column from the `category_translations` to `category` table. And remove
     * this created method.
     *
     * @param  string  $attributeNames
     * @return array
     */
    private function setSameAttributeValueToAllLocale(array $data, ...$attributeNames)
    {
        $requestedLocale = core()->getRequestedLocaleCode();

        foreach ($attributeNames as $attributeName) {
            foreach (core()->getAllLocales() as $locale) {
                if ($requestedLocale == $locale->code) {
                    foreach ($this->getModel()->translatedAttributes as $attribute) {
                        if ($attribute === $attributeName) {
                            $data[$locale->code][$attribute] = $data[$requestedLocale][$attribute] ?? $data[$data['locale']][$attribute];
                        }
                    }
                }
            }
        }

        return $data;
    }
}
