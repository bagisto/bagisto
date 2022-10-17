<?php

namespace Webkul\Attribute\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Admin\DataGrids\AttributeDataGrid;
use Webkul\Core\Contracts\Validations\Code;

class AttributeController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(protected AttributeRepository $attributeRepository)
    {
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
            return app(AttributeDataGrid::class)->toJson();
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
            'code'       => ['required', 'not_in:type,attribute_family_id', 'unique:attributes,code', new Code],
            'admin_name' => 'required',
            'type'       => 'required',
        ]);

        Event::dispatch('catalog.attribute.create.before');

        $attribute = $this->attributeRepository->create(array_merge(request()->all(), [
            'is_user_defined' => 1,
        ]));

        Event::dispatch('catalog.attribute.create.after', $attribute);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Attribute']));

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
        $attribute = $this->attributeRepository->findOrFail($id);

        return view($this->_config['view'], compact('attribute'));
    }

    /**
     * Get attribute options associated with attribute.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function getAttributeOptions($id)
    {
        $attribute = $this->attributeRepository->findOrFail($id);

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

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Attribute']));

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
        $attribute = $this->attributeRepository->findOrFail($id);

        if (! $attribute->is_user_defined) {
            return response()->json([
                'message' => trans('admin::app.response.user-define-error', ['name' => 'Attribute']),
            ], 400);
        }

        try {
            Event::dispatch('catalog.attribute.delete.before', $id);

            $this->attributeRepository->delete($id);

            Event::dispatch('catalog.attribute.delete.after', $id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Attribute'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Attribute'])], 500);
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
                    session()->flash('error', trans('admin::app.response.user-define-error', ['name' => 'Attribute']));

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
}
