<?php

namespace Webkul\CatalogRule\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Admin\DataGrids\CatalogRuleDataGrid;
use Webkul\CatalogRule\Helpers\CatalogRuleIndex;
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
     * To hold catalog repository instance.
     *
     * @var \Webkul\CatalogRule\Repositories\CatalogRuleRepository
     */
    protected $catalogRuleRepository;

    /**
     * Catalog rule index.
     *
     * @var \Webkul\CatalogRule\Helpers\CatalogRuleIndex
     */
    protected $catalogRuleIndexHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CatalogRule\Repositories\CatalogRuleRepository  $catalogRuleRepository
     * @param  \Webkul\CatalogRule\Helpers\CatalogRuleIndex  $catalogRuleIndexHelper
     * @return void
     */
    public function __construct(
        CatalogRuleRepository $catalogRuleRepository,
        CatalogRuleIndex $catalogRuleIndexHelper
    ) {
        $this->_config = request('_config');

        $this->catalogRuleRepository = $catalogRuleRepository;

        $this->catalogRuleIndexHelper = $catalogRuleIndexHelper;
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
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'            => 'required',
            'channels'        => 'required|array|min:1',
            'customer_groups' => 'required|array|min:1',
            'starts_from'     => 'nullable|date',
            'ends_till'       => 'nullable|date|after_or_equal:starts_from',
            'action_type'     => 'required',
            'discount_amount' => 'required|numeric',
        ]);

        $data = request()->all();

        $this->catalogRuleRepository->create($data);

        $this->catalogRuleIndexHelper->reindexComplete();

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name'            => 'required',
            'channels'        => 'required|array|min:1',
            'customer_groups' => 'required|array|min:1',
            'starts_from'     => 'nullable|date',
            'ends_till'       => 'nullable|date|after_or_equal:starts_from',
            'action_type'     => 'required',
            'discount_amount' => 'required|numeric',
        ]);

        $this->catalogRuleRepository->findOrFail($id);

        $this->catalogRuleRepository->update(request()->all(), $id);

        $this->catalogRuleIndexHelper->reindexComplete();

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
            $this->catalogRuleRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Catalog Rule'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Catalog Rule'])], 400);
    }
}
