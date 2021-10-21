<?php

namespace Webkul\Inventory\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Inventory\Repositories\InventorySourceRepository;

class InventorySourceController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * InventorySourceRepository object
     *
     * @var \Webkul\Inventory\Repositories\InventorySourceRepository
     */
    protected $inventorySourceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySourceRepository
     * @return void
     */
    public function __construct(InventorySourceRepository $inventorySourceRepository)
    {
        $this->inventorySourceRepository = $inventorySourceRepository;

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
            'code'           => ['required', 'unique:inventory_sources,code', new \Webkul\Core\Contracts\Validations\Code],
            'name'           => 'required',
            'contact_name'   => 'required',
            'contact_email'  => 'required|email',
            'contact_number' => 'required',
            'street'         => 'required',
            'country'        => 'required',
            'state'          => 'required',
            'city'           => 'required',
            'postcode'       => 'required',
        ]);

        $data = request()->all();

        $data['status'] = !isset($data['status']) ? 0 : 1;

        Event::dispatch('inventory.inventory_source.create.before');

        $inventorySource = $this->inventorySourceRepository->create($data);

        Event::dispatch('inventory.inventory_source.create.after', $inventorySource);

        session()->flash('success', trans('admin::app.settings.inventory_sources.create-success'));

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
        $inventorySource = $this->inventorySourceRepository->findOrFail($id);

        return view($this->_config['view'], compact('inventorySource'));
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
            'code'           => ['required', 'unique:inventory_sources,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name'           => 'required',
            'contact_name'   => 'required',
            'contact_email'  => 'required|email',
            'contact_number' => 'required',
            'street'         => 'required',
            'country'        => 'required',
            'state'          => 'required',
            'city'           => 'required',
            'postcode'       => 'required',
        ]);

        $data = request()->all();

        $data['status'] = !isset($data['status']) ? 0 : 1;

        Event::dispatch('inventory.inventory_source.update.before', $id);

        $inventorySource = $this->inventorySourceRepository->update($data, $id);

        Event::dispatch('inventory.inventory_source.update.after', $inventorySource);

        session()->flash('success', trans('admin::app.settings.inventory_sources.update-success'));

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
        $inventorySource = $this->inventorySourceRepository->findOrFail($id);

        if ($this->inventorySourceRepository->count() == 1) {
            session()->flash('error', trans('admin::app.settings.inventory_sources.last-delete-error'));
        } else {
            try {
                Event::dispatch('inventory.inventory_source.delete.before', $id);

                $this->inventorySourceRepository->delete($id);

                Event::dispatch('inventory.inventory_source.delete.after', $id);

                session()->flash('success', trans('admin::app.settings.inventory_sources.delete-success'));

                return response()->json(['message' => true], 200);
            } catch (\Exception $e) {
                report($e);
                
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Inventory source']));
            }
        }

        return response()->json(['message' => false], 400);
    }
}