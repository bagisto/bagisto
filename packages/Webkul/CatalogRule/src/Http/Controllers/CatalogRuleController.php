<?php

namespace Webkul\CatalogRule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\CatalogRuleDataGrid;
use Webkul\CatalogRule\Helpers\CatalogRuleIndex;
use Webkul\CatalogRule\Http\Requests\CatalogRuleRequest;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;

class CatalogRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route.
     *
     * @param array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CatalogRule\Repositories\CatalogRuleRepository  $catalogRuleRepository
     * @param  \Webkul\CatalogRule\Helpers\CatalogRuleIndex  $catalogRuleIndexHelper
     * @return void
     */
    public function __construct(
        protected CatalogRuleRepository $catalogRuleRepository,
        protected CatalogRuleIndex $catalogRuleIndexHelper
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CatalogRuleDataGrid::class)->toJson();
        }

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
     * @param  \Webkul\CatalogRule\Http\Requests\CatalogRuleRequest  $catalogRuleRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogRuleRequest $catalogRuleRequest)
    {
        Event::dispatch('promotions.catalog_rule.create.before');

        $catalogRule = $this->catalogRuleRepository->create($catalogRuleRequest->all());

        Event::dispatch('promotions.catalog_rule.create.after', $catalogRule);

        $this->catalogRuleIndexHelper->reIndexComplete();

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Catalog Rule']));

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
        $catalogRule = $this->catalogRuleRepository->findOrFail($id);

        return view($this->_config['view'], compact('catalogRule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\CatalogRule\Http\Requests\CatalogRuleRequest  $catalogRuleRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogRuleRequest $catalogRuleRequest, $id)
    {
        $this->catalogRuleRepository->findOrFail($id);

        Event::dispatch('promotions.catalog_rule.update.before', $id);

        $catalogRule = $this->catalogRuleRepository->update($catalogRuleRequest->all(), $id);

        Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);

        $this->catalogRuleIndexHelper->reIndexComplete();

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Catalog Rule']));

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
        $this->catalogRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.catalog_rule.delete.before', $id);

            $this->catalogRuleRepository->delete($id);

            Event::dispatch('promotions.catalog_rule.delete.after', $id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Catalog Rule'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Catalog Rule'])], 400);
    }
}
