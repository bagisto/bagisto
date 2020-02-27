<?php

namespace Webkul\Category\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Category\Contracts\Category';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.category.create.before');

        if (isset($data['locale']) && $data['locale'] == 'all') {
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

        Event::dispatch('catalog.category.create.after', $category);

        return $category;
    }

    /**
     * Specify category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getCategoryTree($id = null)
    {
        return $id
               ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
               : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Specify category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getCategoryTreeWithoutDescendant($id = null)
    {
        return $id
               ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->whereNotDescendantOf($id)->get()->toTree()
               : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Get root categories
     *
     * @return mixed
     */
    public function getRootCategories()
    {
        return $this->getModel()->where('parent_id', NULL)->get();
    }

    /**
     * get visible category tree
     *
     * @param integer $id
     * @return mixed
     */
    public function getVisibleCategoryTree($id = null)
    {
        static $categories = [];

        if(array_key_exists($id, $categories)) {
            return $categories[$id];
        }

        return $categories[$id] = $id
               ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsOf($id)->toTree()
               : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param integer $id
     * @param string  $slug
     * @return boolean
     */
    public function isSlugUnique($id, $slug)
    {
        $exists = CategoryTranslation::where('category_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    /**
     * Retrive category from slug
     *
     * @param string $slug
     * @return mixed
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
     * @param string $urlPath
     *
     * @return mixed
     */
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('url_path', $urlPath)->first();
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $category = $this->find($id);

        Event::dispatch('catalog.category.update.before', $id);

        $category->update($data);

        $this->uploadImages($data, $category);

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        Event::dispatch('catalog.category.update.after', $id);

        return $category;
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.category.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.category.delete.after', $id);
    }

    /**
     * @param array $data
     * @param mixed $category
     * @return void
     */
    public function uploadImages($data, $category, $type = "image")
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

    public function getPartial($columns = null)
    {
        $categories = $this->model->all();
        $trimmed = array();

        foreach ($categories as $key => $category) {
            if ($category->name != null || $category->name != "") {
                $trimmed[$key] = [
                    'id'   => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug
                ];
            }
        }

        return $trimmed;
    }
}