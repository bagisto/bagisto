<?php

namespace Webkul\Category\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Core\Eloquent\Repository;

class CategoryRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Category\Contracts\Category';
    }

    /**
     * Create category.
     *
     * @param  array  $data
     * @return \Webkul\Category\Contracts\Category
     */
    public function create(array $data)
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];

                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        $category = $this->model->create($data);

        $this->uploadImages($data, $category);

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        return $category;
    }

    /**
     * Update category.
     *
     * @param  array  $data
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

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        return $category;
    }

    /**
     * Specify category tree.
     *
     * @param  int  $id
     * @return \Webkul\Category\Contracts\Category
     */
    public function getCategoryTree($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Specify category tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getCategoryTreeWithoutDescendant($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->whereNotDescendantOf($id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Get root categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRootCategories()
    {
        return $this->getModel()->where('parent_id', null)->get();
    }

    /**
     * get visible category tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getVisibleCategoryTree($id = null)
    {
        static $categories = [];

        if (array_key_exists($id, $categories)) {
            return $categories[$id];
        }

        return $categories[$id] = $id
            ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsAndSelf($id)->toTree($id)
            : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();
    }

    /**
     * Checks slug is unique or not based on locale.
     *
     * @param  int  $id
     * @param  string  $slug
     * @return bool
     */
    public function isSlugUnique($id, $slug)
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
     * @param string $slug
     * @return \Webkul\Category\Contracts\Category
     */
    public function findBySlug($slug)
    {
        $category = $this->model->whereTranslation('slug', $slug)->first();

        if ($category) {
            return $category;
        }
    }

    /**
     * Retrieve category from slug.
     *
     * @param string $slug
     * @return \Webkul\Category\Contracts\Category
     */
    public function findBySlugOrFail($slug)
    {
        $category = $this->model->whereTranslation('slug', $slug)->first();

        if ($category) {
            return $category;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }

    /**
     * Find by path.
     *
     * @param  string  $urlPath
     * @return \Webkul\Category\Contracts\Category
     */
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('url_path', $urlPath)->first();
    }

    /**
     * Upload category's images.
     *
     * @param  array  $data
     * @param  \Webkul\Category\Contracts\Category  $category
     * @param  string $type
     * @return void
     */
    public function uploadImages($data, $category, $type = 'image')
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;

                $dir = 'category/' . $category->id;

                if ($request->hasFile($file)) {
                    if ($category->{$type}) {
                        Storage::delete($category->{$type});
                    }

                    $category->{$type} = $request->file($file)->store($dir);

                    $category->save();
                }
            }
        } else {
            if ($category->{$type}) {
                Storage::delete($category->{$type});
            }

            $category->{$type} = null;
            
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
        $categories = $this->model->all();

        $trimmed = [];

        foreach ($categories as $key => $category) {
            if (
                $category->name != null
                || $category->name != ''
            ) {
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
     * @param  array  $data
     * @param  string $attributeNames
     * @return array
     */
    private function setSameAttributeValueToAllLocale(array $data, ...$attributeNames)
    {
        $requestedLocale = core()->getRequestedLocaleCode();

        $model = app()->make($this->model());

        foreach ($attributeNames as $attributeName) {
            foreach (core()->getAllLocales() as $locale) {
                if ($requestedLocale == $locale->code) { 
                    foreach ($model->translatedAttributes as $attribute) {
                        if ($attribute === $attributeName) {
                            $data[$locale->code][$attribute] = isset($data[$requestedLocale][$attribute])
                                ? $data[$requestedLocale][$attribute]
                                : $data[$data['locale']][$attribute];
                        }
                    }
                }
            }
        }

        return $data;
    }
}
