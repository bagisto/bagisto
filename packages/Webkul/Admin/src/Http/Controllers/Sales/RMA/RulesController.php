<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\RulesDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\RMA\Repositories\RMARuleRepository;

class RulesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RMARuleRepository $rmaRulesRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(RulesDataGrid::class)->process();
        }

        return view('admin::sales.rma.rules.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name'        => 'required',
            'status'      => 'required|boolean',
            'description' => 'required',
        ]);

        $this->rmaRulesRepository->create(request()->only(
            'name',
            'status',
            'description',
            'return_period'
        ));

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rules.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $reason = $this->rmaRulesRepository->findOrFail($id);

        return new JsonResponse($reason);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        $this->validate(request(), [
            'name'             => 'required',
            'status'           => 'required|boolean',
            'description'      => 'required',
        ]);

        $this->rmaRulesRepository->update(request()->only(
            'name',
            'status',
            'description',
            'return_period'
        ), $id);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rules.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->rmaRulesRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rules.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rules.index.datagrid.reason-error'),
            ], 500);
        }
    }

    /**
     * Mass update rules status.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->rmaRulesRepository
            ->whereIn('id', $request->indices)
            ->update(['status' => $request->value]);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rules.edit.mass-update-success'),
        ]);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            $this->rmaRulesRepository->whereIn('id', $request->indices)->delete();

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rules.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rules.index.datagrid.reason-error'),
            ], 500);
        }
    }
}
