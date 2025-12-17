<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\StatusDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\RMA\Repositories\RMAStatusRepository;

class StatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

    /**
     * RMA status listing.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(StatusDataGrid::class)->process();
        }

        return view('admin::sales.rma.statuses.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'title'  => 'required|unique:rma_statuses,title',
            'status' => 'required|boolean',
        ]);

        $this->rmaStatusRepository->create(request()->only('title', 'status', 'color'));

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rma-status.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $reason = $this->rmaStatusRepository->findOrFail($id);

        return new JsonResponse($reason);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        $this->validate(request(), [
            'title'  => 'required|unique:rma_statuses,title,'.$id,
            'status' => 'required|boolean',
        ]);

        $this->rmaStatusRepository->update(request()->only('title', 'status', 'color'), $id);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rma-status.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->rmaStatusRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rma-status.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rma-status.index.datagrid.reason-error'),
            ], 500);
        }
    }

    /**
     * Mass update rma status.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->rmaStatusRepository
            ->whereIn('id', $request->indices)
            ->update(['status' => $request->value]);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.rma-status.edit.mass-update-success'),
        ]);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            $this->rmaStatusRepository
                ->where('default', 0)
                ->whereIn('id', $request->indices)
                ->delete();

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rma-status.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.rma-status.index.datagrid.reason-error'),
            ], 500);
        }
    }
}
