<?php

namespace Webkul\Category\Http\Controllers;

use Webkul\Admin\DataGrids\CategoryDataGrid;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Http\Requests\CategoryRequest;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Models\Channel;

class CategoryController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Category repository instance.
     *
     * @var \Webkul\Category\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * Attribute repository instance.
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
    ) {
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
        if (request()->ajax()) {
            return app(CategoryDataGrid::class)->toJson();
        }

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

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Category\Http\Requests\CategoryRequest  $categoryRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $categoryRequest)
    {
        $this->categoryRepository->create($categoryRequest->all());

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

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view($this->_config['view'], compact('category', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Category\Http\Requests\CategoryRequest  $categoryRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, $id)
    {
        $this->categoryRepository->update($categoryRequest->all(), $id);

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
            return response()->json(['message' => trans('admin::app.response.delete-category-root', ['name' => 'Category'])], 400);
        }

        try {
            $this->categoryRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Category'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Category'])], 500);
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

                        $this->categoryRepository->delete($categoryId);
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
    public function categoryProductCount()
    {
        $product_count = 0;
        $indexes = explode(',', request()->input('indexes'));

        foreach ($indexes as $index) {
            $category = $this->categoryRepository->find($index);
            $product_count += $category->products->count();
        }

        return response()->json(['product_count' => $product_count]);
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
