<?php

namespace Webkul\Category\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Category\Models\Category;
use Illuminate\Container\Container as App;
use Webkul\Category\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CategoryRepository extends Repository
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Category\Models\Category';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        if(isset($data['locale']) && $data['locale'] == 'all') {
            $model = app()->make($this->model());

            foreach(core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if(isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                    }
                }
            }
        }

        $category = $this->model->create($data);

        $this->uploadImages($data, $category);

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
            ? Category::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : Category::orderBy('position', 'ASC')->get()->toTree();
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
        return CategoryTranslation::where('category_id', '!=', $id)->where('slug', '=', $slug)->first() ? false : true;
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

        if($category)
            return $category;

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
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

        $category->update($data);

        $this->uploadImages($data, $category);

        return $category;
    }

    /**
     * @param array $data
     * @param mixed $category
     * @return void
     */
    public function uploadImages($data, $category,$type = "image")
    {
        if(isset($data[$type])) {
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'category/' . $category->id;

                if(request()->hasFile($file)) {
                    if($category->{$type}) {
                        Storage::delete($category->{$type});
                    }

                    $category->{$type} = request()->file($file)->store($dir);
                    $category->save();
                }
            }
        } else {
            if($category->{$type}) {
                Storage::delete($category->{$type});
            }

            $category->{$type} = null;
            $category->save();
        }
    }
}