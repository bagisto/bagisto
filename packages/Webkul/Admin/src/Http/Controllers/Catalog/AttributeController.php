<?php

namespace Webkul\Admin\Http\Controllers\Catalog;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Catalog\AttributeDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Rules\Code;
use Webkul\Product\Repositories\ProductRepository;

class AttributeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(AttributeDataGrid::class)->process();
        }

        return view('admin::catalog.attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $locales = core()->getAllLocales();

        return view('admin::catalog.attributes.create', compact('locales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code'          => ['required', 'not_in:type,attribute_family_id', 'unique:attributes,code', new Code],
            'admin_name'    => 'required',
            'type'          => 'required',
            'default_value' => 'integer',
        ]);

        $requestData = request()->all();

        $requestData['default_value'] ??= null;

        Event::dispatch('catalog.attribute.create.before');

        $attribute = $this->attributeRepository->create($requestData);

        Event::dispatch('catalog.attribute.create.after', $attribute);

        session()->flash('success', trans('admin::app.catalog.attributes.create-success'));

        return redirect()->route('admin.catalog.attributes.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        $locales = core()->getAllLocales();

        return view('admin::catalog.attributes.edit', compact('attribute', 'locales'));
    }

    /**
     * Get attribute options associated with attribute.
     *
     * @return \Illuminate\View\View
     */
    public function getAttributeOptions(int $id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        return $attribute->options()->orderBy('sort_order')->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'code'          => ['required', 'unique:attributes,code,'.$id, new Code],
            'admin_name'    => 'required',
            'type'          => 'required',
            'default_value' => 'integer',
        ]);

        $requestData = request()->all();

        if (! $requestData['default_value']) {
            $requestData['default_value'] = null;
        }

        Event::dispatch('catalog.attribute.update.before', $id);

        $attribute = $this->attributeRepository->update($requestData, $id);

        Event::dispatch('catalog.attribute.update.after', $attribute);

        session()->flash('success', trans('admin::app.catalog.attributes.update-success'));

        return redirect()->route('admin.catalog.attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        if (! $attribute->is_user_defined) {
            return response()->json([
                'message' => trans('admin::app.catalog.attributes.user-define-error'),
            ], 400);
        }

        try {
            Event::dispatch('catalog.attribute.delete.before', $id);

            $this->attributeRepository->delete($id);

            Event::dispatch('catalog.attribute.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.catalog.attributes.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.attributes.delete-failed'),
        ], 500);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        foreach ($indices as $index) {
            $attribute = $this->attributeRepository->find($index);

            if (! $attribute->is_user_defined) {
                return response()->json([
                    'message' => trans('admin::app.catalog.attributes.delete-failed'),
                ], 422);
            }
        }

        foreach ($indices as $index) {
            Event::dispatch('catalog.attribute.delete.before', $index);

            $this->attributeRepository->delete($index);

            Event::dispatch('catalog.attribute.delete.after', $index);
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.attributes.index.datagrid.mass-delete-success'),
        ]);
    }

    /**
     * Get super attributes of product.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productSuperAttributes(int $id)
    {
        $product = $this->productRepository->findOrFail($id);

        $superAttributes = $this->productRepository->getSuperAttributes($product);

        return response()->json([
            'data'  => $superAttributes,
        ]);
    }
}
