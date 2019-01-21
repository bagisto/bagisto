<?php

namespace Webkul\Attribute\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Event;

/**
 * Catalog attribute controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository  $attribute
     * @return void
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:attributes,code', new \Webkul\Core\Contracts\Validations\Code],
            'admin_name' => 'required',
            'type' => 'required'
        ]);

        $data = request()->all();

        $data['is_user_defined'] = 1;

        $attribute = $this->attribute->create($data);

        // Event::fire('after.attribute.created', $attribute);

        session()->flash('success', 'Attribute created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = $this->attribute->find($id);

        return view($this->_config['view'], compact('attribute'));
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
            'code' => ['required', 'unique:attributes,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'admin_name' => 'required',
            'type' => 'required'
        ]);

        $attribute = $this->attribute->update(request()->all(), $id);

        // Event::fire('after.attribute.updated', $attribute);

        session()->flash('success', 'Attribute updated successfully.');

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
        $attribute = $this->attribute->findOrFail($id);

        if(!$attribute->is_user_defined) {
            session()->flash('error', trans('admin::app.response.user-define-error', ['name' => 'attribute']));
        } else {
            try {
                // Event::fire('after.attribute.deleted', $attribute);

                $this->attribute->delete($id);

                session()->flash('success', 'Attribute deleted successfully.');

            } catch(\Exception $e) {
                session()->flash('error', trans('admin::app.response.attribute-error', ['name' => 'Attribute']));
            }
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from database
     *
     * @return response \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $attribute = $this->attribute->findOrFail($value);

                try {
                    if (! $attribute->is_user_defined) {
                        continue;
                    } else {
                        $this->attribute->delete($value);
                    }
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'attributes']));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'attributes']));

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}