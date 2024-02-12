<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Catalog\CategoryDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\CategoryRequest;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\Http\Resources\CategoryTreeResource;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Repositories\ChannelRepository;

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
    ) {
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

        $category = $this->categoryRepository->create($categoryRequest->only([
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
            'banner_path',
        ]));

        Event::dispatch('catalog.category.create.after', $category);

        session()->flash('success', trans('admin::app.catalog.categories.create-success'));

        return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $category = $this->categoryRepository->findOrFail($id);

        $categories = $this->categoryRepository->getCategoryTreeWithoutDescendant($id);

        $attributes = $this->attributeRepository->findWhere(['is_filterable' => 1]);

        return view('admin::catalog.categories.edit', compact('category', 'categories', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, int $id)
    {
        Event::dispatch('catalog.category.update.before', $id);

        $category = $this->categoryRepository->update($categoryRequest->only(
            'locale',
            'parent_id',
            'logo_path',
            'banner_path',
            'position',
            'display_mode',
            'status',
            'attributes',
            core()->getCurrentLocale()->code
        ), $id);

        Event::dispatch('catalog.category.update.after', $category);

        session()->flash('success', trans('admin::app.catalog.categories.update-success'));

        return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryRepository->findOrFail($id);

        if (! $this->isCategoryDeletable($category)) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.delete-category-root'),
            ], 400);
        }

        try {
            Event::dispatch('catalog.category.delete.before', $id);

            $category->delete($id);

            Event::dispatch('catalog.category.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.delete-success', [
                    'name' => trans('admin::app.catalog.categories.category'),
                ]),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.delete-failed', [
                    'name' => trans('admin::app.catalog.categories.category'),
                ]),
            ], 500);
        }
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $suppressFlash = true;

        $categoryIds = $massDestroyRequest->input('indices');

        foreach ($categoryIds as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);

            if (isset($category)) {
                if (! $this->isCategoryDeletable($category)) {
                    $suppressFlash = false;

                    return new JsonResponse(['message' => trans('admin::app.catalog.categories.delete-category-root')], 400);
                } else {
                    try {
                        $suppressFlash = true;

                        Event::dispatch('catalog.category.delete.before', $categoryId);

                        $this->categoryRepository->delete($categoryId);

                        Event::dispatch('catalog.category.delete.after', $categoryId);
                    } catch (\Exception $e) {
                        return new JsonResponse([
                            'message' => trans('admin::app.catalog.categories.delete-failed'),
                        ], 500);
                    }
                }
            }
        }

        if (
            count($categoryIds) != 1
            || $suppressFlash == true
        ) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.delete-success'),
            ]);
        }

        return redirect()->route('admin.catalog.categories.index');
    }

    /**
     * Mass update Category.
     *
     * @return \Illuminate\Http\JsonResponse
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

            return new JsonResponse([
                'message' => trans('admin::app.catalog.categories.update-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
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
        if ($category->id === 1) {
            return false;
        }

        return ! $this->channelRepository->pluck('root_category_id')->contains($category->id);
    }

    /**
     * Get all categories in tree format.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tree()
    {
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);

        return CategoryTreeResource::collection($categories);
    }

    /**
     * Result of search customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $results = [];

        $categories = $this->categoryRepository->scopeQuery(function ($query) {
            return $query
                ->select('categories.*')
                ->leftJoin('category_translations', function ($query) {
                    $query->on('categories.id', '=', 'category_translations.category_id')
                        ->where('category_translations.locale', app()->getLocale());
                })
                ->where('category_translations.name', 'like', '%'.urldecode(request()->input('query')).'%')
                ->orderBy('created_at', 'desc');
        })->paginate(10);

        return response()->json($categories);
    }
}
