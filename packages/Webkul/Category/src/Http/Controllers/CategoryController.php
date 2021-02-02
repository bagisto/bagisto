<?php

namespace Webkul\Category\Http\Controllers;

use Webkul\Core\Models\Channel;
use Illuminate\Support\Facades\Event;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class CategoryController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * CategoryRepository object
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Category\Repositories\CategoryRepository  $categoryRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        AttributeRepository $attributeRepository
    )
    {
        $this->categoryRepository = $categoryRepository;

        $this->attributeRepository = $attributeRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->categoryRepository->getCategoryTree(null, ['id']);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' =>  1]);

        return view($this->_config['view'], compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'slug'        => ['required', 'unique:category_translations,slug', new \Webkul\Core\Contracts\Validations\Slug],
            'name'        => 'required',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
            'description' => 'required_if:display_mode,==,description_only,products_and_description',
        ]);

        $category = $this->categoryRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTreeWithoutDescendant($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' =>  1]);

        return view($this->_config['view'], compact('category', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $locale = request()->get('locale') ?: app()->getLocale();

        $this->validate(request(), [
            $locale . '.slug' => ['required', new \Webkul\Core\Contracts\Validations\Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->categoryRepository->isSlugUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Category']));
                }
            }],
            $locale . '.name' => 'required',
            'image.*'         => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $this->categoryRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->findOrFail($id);

        if ($this->isCategoryDeletable($category)) {
            session()->flash('warning', trans('admin::app.response.delete-category-root', ['name' => 'Category']));
        } else {
            try {
                Event::dispatch('catalog.category.delete.before', $id);

                if ($category->products->count() > 0) {
                    $category->products()->delete();
                }

                $category->delete();

                Event::dispatch('catalog.category.delete.after', $id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Category']));

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Category']));
            }
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = true;
        $categoryIds = explode(',', request()->input('indexes'));

        foreach ($categoryIds as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);

            if (isset($category)) {
                if ($this->isCategoryDeletable($category)) {
                    $suppressFlash = false;
                    session()->flash('warning', trans('admin::app.response.delete-category-root', ['name' => 'Category']));
                } else {
                    try {
                        $suppressFlash = true;
                        Event::dispatch('catalog.category.delete.before', $categoryId);

                        if ($category->products->count() > 0) {
                            $category->products()->delete();
                        }

                        $category->delete();

                        Event::dispatch('catalog.category.delete.after', $categoryId);
                    } catch (\Exception $e) {
                        session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Category']));
                    }
                }
            }
        }

        if (count($categoryIds) != 1 || $suppressFlash == true) {
            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'Category']));
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Get category product count.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryProductCount() {
        $indexes = explode(",", request()->input('indexes'));
        $product_count = 0;

        foreach($indexes as $index) {
            $category = $this->categoryRepository->find($index);
            $product_count += $category->products->count();
        }

        return response()->json(['product_count' => $product_count], 200);
    }

    /**
     * Check whether the current category is deletable or not.
     *
     * This method will fetch all root category ids from the channel. If `id` is present,
     * then it is not deletable.
     *
     * @param  \Webkul\Category\Models\Category $category
     * @return bool
     */
    private function isCategoryDeletable($category)
    {
        static $rootIdInChannels;

        if (! $rootIdInChannels) {
            $rootIdInChannels = Channel::pluck('root_category_id');
        }

        return $category->id === 1 || $rootIdInChannels->contains($category->id);
    }
}