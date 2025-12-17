<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\ReasonDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\RMA\Repositories\RMAReasonRepository;
use Webkul\RMA\Repositories\RMAReasonResolutionRepository;

class ReasonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RMAReasonRepository $rmaReasonRepository,
        protected RMAReasonResolutionRepository $rmaReasonResolutionsRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ReasonDataGrid::class)->process();
        }

        return view('admin::sales.rma.reasons.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'title'           => 'required',
            'status'          => 'required|boolean',
            'position'        => 'required',
            'resolution_type' => 'required|array|min:1',
        ]);

        $rmaReason = $this->rmaReasonRepository->create(request()->only('title', 'status', 'position'));

        foreach (request()->resolution_type as $resolutionType) {
            $this->rmaReasonResolutionsRepository->create([
                'rma_reason_id'   => $rmaReason->id,
                'resolution_type' => $resolutionType,
            ]);
        }

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.reasons.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): JsonResponse
    {
        $reason = $this->rmaReasonRepository->findOrFail($id);

        $reason->reasonResolutions = $reason->reasonResolutions->pluck('resolution_type')->toArray();

        return new JsonResponse($reason);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): JsonResponse
    {
        $this->validate(request(), [
            'title'           => 'required',
            'status'          => 'required|boolean',
            'position'        => 'required',
            'resolution_type' => 'required|array|min:1',
        ]);

        $rmaReason = $this->rmaReasonRepository->update(request()->only('title', 'status', 'position'), $id);

        $resolutionTypes = request()->resolution_type ?? [];

        $existResolution = $this->rmaReasonResolutionsRepository->where('rma_reason_id', $rmaReason->id)->get();

        if (! empty($existResolution)) {
            $this->rmaReasonResolutionsRepository->whereNotIn('resolution_type', $resolutionTypes)
                ->where('rma_reason_id', $rmaReason->id)
                ->delete();
        }

        foreach ($resolutionTypes as $resolutionType) {
            $this->rmaReasonResolutionsRepository->updateOrCreate([
                'rma_reason_id'   => $rmaReason->id,
                'resolution_type' => $resolutionType,
            ]);
        }

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.reasons.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->rmaReasonRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.reasons.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.reasons.index.datagrid.reason-error'),
            ], 500);
        }
    }

    /**
     * Mass update reasons status.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->rmaReasonRepository
            ->whereIn('id', $request->indices)
            ->update(['status' => $request->value]);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.reasons.edit.mass-update-success'),
        ]);
    }

    /**
     * Remove the specified resources from database.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            $this->rmaReasonRepository->whereIn('id', $request->indices)->delete();

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.reasons.index.datagrid.mass-delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.reasons.index.datagrid.reason-error'),
            ], 500);
        }
    }
}
