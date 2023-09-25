<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Admin\DataGrids\Marketing\Promotions\CatalogRuleDataGrid;
use Webkul\Admin\Http\Requests\CatalogRuleRequest;

class CatalogRuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CatalogRuleRepository $catalogRuleRepository)
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
            return app(CatalogRuleDataGrid::class)->toJson();
        }

        return view('admin::marketing.promotions.catalog-rules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::marketing.promotions.catalog-rules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogRuleRequest $catalogRuleRequest)
    {
        Event::dispatch('promotions.catalog_rule.create.before');

        $catalogRule = $this->catalogRuleRepository->create($catalogRuleRequest->all());

        Event::dispatch('promotions.catalog_rule.create.after', $catalogRule);

        session()->flash('success', trans('admin::app.marketing.promotions.catalog-rules.create-success'));

        return redirect()->route('admin.marketing.promotions.catalog_rules.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $catalogRule = $this->catalogRuleRepository->findOrFail($id);

        return view('admin::marketing.promotions.catalog-rules.edit', compact('catalogRule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogRuleRequest $catalogRuleRequest, $id)
    {
        $this->catalogRuleRepository->findOrFail($id);

        Event::dispatch('promotions.catalog_rule.update.before', $id);

        $catalogRule = $this->catalogRuleRepository->update($catalogRuleRequest->all(), $id);

        Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);

        session()->flash('success', trans('admin::app.marketing.promotions.catalog-rules.update-success'));

        return redirect()->route('admin.marketing.promotions.catalog_rules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->catalogRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.catalog_rule.delete.before', $id);

            $this->catalogRuleRepository->delete($id);

            Event::dispatch('promotions.catalog_rule.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.marketing.promotions.catalog-rules.delete-success')
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.catalog-rules.delete-failed'
        )], 400);
    }
}
