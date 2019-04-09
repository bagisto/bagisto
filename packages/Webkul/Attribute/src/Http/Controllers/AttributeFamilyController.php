<?php

namespace Webkul\Attribute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;


/**
 * Catalog family controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeFamilyController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeFamilyRepository object
     *
     * @var array
     */
    protected $attributeFamily;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeFamilyRepository  $attributeFamily
     * @return void
     */
    public function __construct(AttributeFamily $attributeFamily)
    {
        $this->attributeFamily = $attributeFamily;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @return \Illuminate\Http\Response
     */
    public function create(Attribute $attribute)
    {
        $attributeFamily = $this->attributeFamily->with(['attribute_groups.custom_attributes'])->findOneByField('code', 'default');

        $custom_attributes = $attribute->all(['id', 'code', 'admin_name', 'type']);

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
            'name' => 'required'
        ]);

        $attributeFamily = $this->attributeFamily->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Family']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute, $id)
    {
        $attributeFamily = $this->attributeFamily->with(['attribute_groups.custom_attributes'])->findOrFail($id, ['*']);

        $custom_attributes = $attribute->all(['id', 'code', 'admin_name', 'type']);

        return view($this->_config['view'], compact('attributeFamily', 'custom_attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:attribute_families,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        $attributeFamily = $this->attributeFamily->update(request()->all(), $id);

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
        $attributeFamily = $this->attributeFamily->findOrFail($id);

        if ($this->attributeFamily->count() == 1) {
            session()->flash('error', trans('admin::app.response.last-delete-error', ['name' => 'Family']));

        } else if ($attributeFamily->products()->count()) {
            session()->flash('error', trans('admin::app.response.attribute-product-error', ['name' => 'Attribute family']));
        } else {
            try {
                $this->attributeFamily->delete($id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Family']));

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                session()->flash('error', trans( 'admin::app.response.delete-failed', ['name' => 'Family']));
            }
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy() {
        $suppressFlash = false;

        if (request()->isMethod('delete')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $this->attributeFamily->delete($value);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', ('admin::app.datagrid.mass-ops.delete-success'));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Attribute Family']));

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}