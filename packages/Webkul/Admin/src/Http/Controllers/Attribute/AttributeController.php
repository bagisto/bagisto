<?php

namespace Webkul\Admin\Http\Controllers\Attribute;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Rules\Code;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Admin\DataGrids\AttributeDataGrid;

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
            return app(AttributeDataGrid::class)->toJson();
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
        return view('admin::catalog.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code'       => ['required', 'not_in:type,attribute_family_id', 'unique:attributes,code', new Code],
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        Event::dispatch('catalog.attribute.create.before');

        $attribute = $this->attributeRepository->create(request()->input());

        Event::dispatch('catalog.attribute.create.after', $attribute);

        session()->flash('success', trans('admin::app.catalog.attributes.create-success'));

        return redirect()->route('admin.catalog.attributes.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

        return view('admin::catalog.attributes.edit', compact('attribute'));
    }

    /**
     * Get attribute options associated with attribute.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getAttributeOptions()
    {
        $attribute = $this->attributeRepository->findOrFail(request()->input('id'));

        return $attribute->options()->paginate(50);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'code'       => ['required', 'unique:attributes,code,' . $id, new Code],
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        Event::dispatch('catalog.attribute.update.before', $id);

        $attribute = $this->attributeRepository->update(request()->all(), $id);

        Event::dispatch('catalog.attribute.update.after', $attribute);

        session()->flash('success', trans('admin::app.catalog.attributes.update-success'));

        return redirect()->route('admin.catalog.attributes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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

            return response()->json(['message' => trans('admin::app.catalog.attributes.delete-success')]);
        } catch (\Exception $e) {
        }

        return response()->json(['message' => trans('admin::app.catalog.attributes.delete-failed')], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $index) {
                $attribute = $this->attributeRepository->find($index);

                if (! $attribute->is_user_defined) {
                    session()->flash('error', trans('admin::app.catalog.attributes.user-define-error'));

                    return redirect()->back();
                }
            }

            foreach ($indexes as $index) {
                Event::dispatch('catalog.attribute.delete.before', $index);

                $this->attributeRepository->delete($index);

                Event::dispatch('catalog.attribute.delete.after', $index);
            }

            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'attributes']));
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));
        }

        return redirect()->back();
    }

    /**
     * Get super attributes of product.
     *
     * @param  int  $id
     * @return  \Illuminate\Http\JsonResponse
     */
    public function productSuperAttributes($id)
    {
        $product = $this->productRepository->findOrFail($id);

        $superAttributes = $this->productRepository->getSuperAttributes($product);

        return response()->json([
            'data'  => $superAttributes,
        ]);
    }
}
