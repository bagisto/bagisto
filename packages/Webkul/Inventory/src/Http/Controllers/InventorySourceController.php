<?php

namespace Webkul\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Inventory\Repositories\InventorySourceRepository as InventorySource;

/**
 * Inventory source controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
     * @var array
     */
    protected $inventorySource;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySource
     * @return void
     */
    public function __construct(InventorySource $inventorySource)
    {
        $this->inventorySource = $inventorySource;

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
            'code' => ['required', 'unique:inventory_sources,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required'
        ]);

        $this->inventorySource->create(request()->all());

        session()->flash('success', 'Inventory source created successfully.');

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
        $inventorySource = $this->inventorySource->find($id);

        return view($this->_config['view'], compact('inventorySource'));
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
            'code' => ['required', 'unique:inventory_sources,code,' . $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
        ]);

        $this->inventorySource->update(request()->all(), $id);

        session()->flash('success', 'Inventory source updated successfully.');

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
        if($this->inventorySource->count() == 1) {
            session()->flash('error', 'At least one inventory source is required.');
        } else {
            $this->inventorySource->delete($id);

            session()->flash('success', 'Inventory source deleted successfully.');
        }

        return redirect()->back();
    }
}