<?php

namespace Webkul\Admin\Http\Controllers\Sales\RMA;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Sales\RMA\CustomFieldDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\RMA\Repositories\RMACustomFieldOptionRepository;
use Webkul\RMA\Repositories\RMACustomFieldRepository;

class CustomFieldController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RMACustomFieldRepository $rmaCustomFieldRepository,
        protected RMACustomFieldOptionRepository $rmaCustomFieldOptionRepository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(CustomFieldDataGrid::class)->process();
        }

        return view('admin::sales.rma.custom-fields.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function create(): View
    {
        return view('admin::sales.rma.custom-fields.create');
    }

    /**
     * Store custom fields into database.
     */
    public function store(): RedirectResponse
    {
        $this->validate(request(), [
            'label'    => 'required',
            'code'     => 'required|unique:rma_custom_fields,code',
            'position' => 'required',
            'type'     => 'required|in:text,textarea,select,multiselect,checkbox,radio,date',
            'options'  => 'required_if:type,select,multiselect,checkbox,radio|array|min:1',
            'value'    => 'required_if:type,select,multiselect,checkbox,radio|array|min:1',
        ]);

        $rmaCustomField = $this->rmaCustomFieldRepository->create(request()->only(
            'label',
            'code',
            'position',
            'type',
            'is_required',
            'input_validation',
            'status',
        ));

        if (
            in_array(request()->input('type'), ['select', 'multiselect', 'checkbox', 'radio'])
            && request()->input('options')
        ) {
            $this->rmaCustomFieldOptionRepository->createOption([
                'options' => request()->input('options'),
                'value'   => request()->input('value'),
            ], $rmaCustomField->id);
        }

        session()->flash('success', trans('admin::app.sales.rma.custom-field.create.success'));

        return redirect()->route('admin.sales.rma.custom-field.index');
    }

    /**
     * Edit the specified resource in storage.
     */
    public function edit(int $id): View
    {
        $rmaData = $this->rmaCustomFieldRepository->with('options')->find($id);

        return view('admin::sales.rma.custom-fields.edit', compact('rmaData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id): RedirectResponse
    {
        $this->validate(request(), [
            'label'    => 'required',
            'code'     => ['required', 'unique:rma_custom_fields,code,'.$id],
            'position' => 'required',
            'type'     => 'required|in:text,textarea,select,multiselect,checkbox,radio,date',
            'options'  => 'required_if:type,select,multiselect,checkbox,radio|array|min:1',
            'value'    => 'required_if:type,select,multiselect,checkbox,radio|array|min:1',
        ]);

        $data = request()->only(
            'label',
            'code',
            'position',
            'type',
            'is_required',
            'input_validation',
            'status',
        );

        $data['status'] = $data['status'] ?? 0;

        $data['is_required'] = $data['is_required'] ?? 0;

        $rmaCustomField = $this->rmaCustomFieldRepository->update($data, $id);

        $this->rmaCustomFieldOptionRepository->where('rma_custom_field_id', $rmaCustomField->id)->delete();

        if (
            in_array(request()->input('type'), ['select', 'multiselect', 'checkbox', 'radio'])
            && request()->input('options')
        ) {
            $this->rmaCustomFieldOptionRepository->createOption([
                'options' => request()->input('options'),
                'value'   => request()->input('value'),
            ], $rmaCustomField->id);
        }

        session()->flash('success', trans('admin::app.sales.rma.custom-field.edit.success'));

        return redirect()->route('admin.sales.rma.custom-field.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            Event::dispatch('rma.custom-field.delete.before', $id);

            $this->rmaCustomFieldRepository->delete($id);

            Event::dispatch('rma.custom-field.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.custom-field.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.catalog.attributes.delete-failed'),
        ], 500);
    }

    /**
     * Remove multiple resource from storage at a time.
     */
    public function massUpdate(): JsonResponse
    {
        $this->rmaCustomFieldRepository
            ->whereIn('id', request()->indices)
            ->update(['status' => request()->value]);

        return new JsonResponse([
            'message' => trans('admin::app.sales.rma.custom-field.edit.success'),
        ]);
    }

    /**
     * Update multiple resource from storage at a time.
     */
    public function massDestroy(): JsonResponse
    {
        try {
            $this->rmaCustomFieldRepository->whereIn('id', request()->indices)->delete();

            return new JsonResponse([
                'message' => trans('admin::app.sales.rma.custom-field.index.datagrid.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.catalog.attributes.delete-failed'),
            ], 500);
        }
    }
}
