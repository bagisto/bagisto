<?php

namespace Webkul\Attribute\Http\Controllers;

use Webkul\Admin\DataGrids\AttributeFamilyDataGrid;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class AttributeFamilyController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Attribute family repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;

    /**
     * Attribute repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamilyRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        AttributeFamilyRepository $attributeFamilyRepository,
        AttributeRepository $attributeRepository
    ) {
        $this->attributeFamilyRepository = $attributeFamilyRepository;

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
            return app(AttributeFamilyDataGrid::class)->toJson();
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
        $attributeFamily = $this->attributeFamilyRepository->with(['attribute_groups.custom_attributes'])->findOneByField('code', 'default');

        $custom_attributes = $this->attributeRepository->all(['id', 'code', 'admin_name', 'type']);

        return view($this->_config['view'], compact('custom_attributes', 'attributeFamily'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:attribute_families,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
        ]);

        $attributeFamily = $this->attributeFamilyRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Family']));

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
        $attributeFamily = $this->attributeFamilyRepository->with(['attribute_groups.custom_attributes'])->findOrFail($id, ['*']);

        $custom_attributes = $this->attributeRepository->all(['id', 'code', 'admin_name', 'type']);

        return view($this->_config['view'], compact('attributeFamily', 'custom_attributes'));
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
            'code' => ['required', 'unique:attribute_families,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
        ]);

        $attributeFamily = $this->attributeFamilyRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Family']));

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
        $attributeFamily = $this->attributeFamilyRepository->findOrFail($id);

        if ($this->attributeFamilyRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.response.last-delete-error', ['name' => 'Family']),
            ], 400);
        }

        if ($attributeFamily->products()->count()) {
            return response()->json([
                'message' => trans('admin::app.response.attribute-product-error', ['name' => 'Attribute family']),
            ], 400);
        }

        try {
            $this->attributeFamilyRepository->delete($id);

            return response()->json([
                'message' => trans('admin::app.response.delete-success', ['name' => 'Family']),
            ]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.response.delete-failed', ['name' => 'Family']),
        ], 500);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('delete')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $this->attributeFamilyRepository->delete($value);
                } catch (\Exception $e) {
                    report($e);
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash) {
                session()->flash('success', ('admin::app.datagrid.mass-ops.delete-success'));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Attribute Family']));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}
