<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Core\Http\Requests\MassUpdateRequest;
use Webkul\Core\Http\Requests\MassDestroyRequest;
use Webkul\Category\Http\Requests\CategoryRequest;
use Webkul\Admin\DataGrids\Catalog\CategoryDataGrid;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Admin\DataGrids\Catalog\CategoryProductDataGrid;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected CategoryRepository $categoryRepository,
        protected AttributeRepository $attributeRepository
    )
    {
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

        return view('admin::catalog.categories.index');
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

        return view('admin::catalog.categories.create', compact('categories', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $categoryRequest)
    {
        Event::dispatch('catalog.category.create.before');

        $data = request()->only([
            'locale',
            'name',
            'parent_id',
            'description',
            'slug',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'status',
            'position',
            'display_mode',
            'attributes',
            'logo_path',
            'banner_path'
        ]);

        $category = $this->categoryRepository->create($data);

        Event::dispatch('catalog.category.create.after', $category);

        session()->flash('success', trans('admin::app.catalog.categories.create-success'));

        return redirect()->route('admin.catalog.categories.index');
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

        return view('admin::catalog.categories.edit', compact('category', 'categories', 'attributes'));
    }

    /**
     * Show the products of specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function products($id)
    {
        if (request()->ajax()) {
            return app(ProductDataGrid::class)->toJson();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, $id)
    {
        Event::dispatch('catalog.category.update.before', $id);

        $category = $this->categoryRepository->update($categoryRequest->all(), $id);

        Event::dispatch('catalog.category.update.after', $category);

        session()->flash('success', trans('admin::app.catalog.categories.update-success'));

        return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResource
     */
    public function destroy($id): JsonResource
    {
        $category = $this->categoryRepository->findOrFail($id);

        if ($this->isCategoryDeletable($category)) {
            return new JsonResource(['message' => trans('admin::app.catalog.categories.delete-category-root')], 400);
        }

        try {
            Event::dispatch('catalog.category.delete.before', $id);

            $this->categoryRepository->delete($id);

            Event::dispatch('catalog.category.delete.after', $id);

            return new JsonResource([
                'message' => trans('admin::app.catalog.categories.delete-success', ['name' => 'admin::app.catalog.categories.category'
            ])]);
        } catch (\Exception $e) {
        }

        return new JsonResource([
            'message' => trans('admin::app.catalog.categories.delete-failed', ['name' => 'admin::app.catalog.categories.category'
        ])], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $suppressFlash = true;

        $categoryIds = $massDestroyRequest->input('indices');
        
        foreach ($categoryIds as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);

            if (isset($category)) {
                if ($this->isCategoryDeletable($category)) {
                    $suppressFlash = false;

                    session()->flash('warning', trans('admin::app.response.delete-category-root', ['name' => 'admin::app.catalog.categories.category']));
                } else {
                    try {
                        $suppressFlash = true;

                        Event::dispatch('catalog.category.delete.before', $categoryId);

                        $this->categoryRepository->delete($categoryId);

                        Event::dispatch('catalog.category.delete.after', $categoryId);
                    } catch (\Exception $e) {
                        session()->flash('error', trans('admin::app.catalog.categories.delete-failed', ['name' => 'admin::app.catalog.categories.category']));
                    }
                }
            }
        }

        if (
            count($categoryIds) != 1
            || $suppressFlash == true
        ) {
            session()->flash('success', trans('admin::app.catalog.categories.index.datagrid.delete-success', ['resource' => 'admin::app.catalog.categories.category']));
        }

        return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Mass update Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        try {
            $data = $massUpdateRequest->all();
    
            $categoryIds = $data['indices'];
    
            foreach ($categoryIds as $categoryId) {
                Event::dispatch('catalog.categories.mass-update.before', $categoryId);
    
                $category = $this->categoryRepository->find($categoryId);
    
                $category->status = $massUpdateRequest->input('value');
                
                $category->save();
    
                Event::dispatch('catalog.categories.mass-update.after', $category);
            }
    
            return new JsonResource([
                'message' => trans('admin::app.catalog.categories.update-success')]);
        } catch (\Exception $e) {
            return new JsonResource([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get category product count.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryProductCount()
    {
        $productCount = 0;

        $indices = request()->input('indices');

        foreach ($indices as $index) {
            $category = $this->categoryRepository->find($index);

            $productCount += $category->products->count();
        }

        return response()->json(['product_count' => $productCount]);
    }

    /**
     * Check whether the current category is deletable or not.
     *
     * This method will fetch all root category ids from the channel. If `id` is present,
     * then it is not deletable.
     *
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return bool
     */
    private function isCategoryDeletable($category)
    {
        static $channelRootCategoryIds;

        if (! $channelRootCategoryIds) {
            $channelRootCategoryIds = $this->channelRepository->pluck('root_category_id');
        }

        return $category->id === 1 || $channelRootCategoryIds->contains($category->id);
    }

    /**
     * Result of search customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $results = [];

        $categories = $this->categoryRepository->scopeQuery(function($query) {
            return $query
                ->select('categories.*')
                ->leftJoin('category_translations', function ($query) {
                    $query->on('categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', app()->getLocale());
                })
                ->where('category_translations.name', 'like', '%' . urldecode(request()->input('query')) . '%')
                ->orderBy('created_at', 'desc');
        })->paginate(10);

        return response()->json($categories);
    }
}
