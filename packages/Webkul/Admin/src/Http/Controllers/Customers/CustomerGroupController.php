<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\Customers\GroupDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Rules\Code;
use Webkul\Customer\Repositories\CustomerGroupRepository;

class CustomerGroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerGroupRepository $customerGroupRepository) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(GroupDataGrid::class)->process();
        }

        return view('admin::customers.groups.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'code' => ['required', 'unique:customer_groups,code', new Code],
            'name' => 'required',
        ]);

        Event::dispatch('customer.customer_group.create.before');

        $data = array_merge(request()->only([
            'code',
            'name',
        ]), [
            'is_user_defined' => 1,
        ]);

        $customerGroup = $this->customerGroupRepository->create($data);

        Event::dispatch('customer.customer_group.create.after', $customerGroup);

        return new JsonResponse([
            'message' => trans('admin::app.customers.groups.index.create.success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $id = request()->input('id');

        $this->validate(request(), [
            'code' => ['required', 'unique:customer_groups,code,'.$id, new Code],
            'name' => 'required',
        ]);

        Event::dispatch('customer.customer_group.update.before', $id);

        $customerGroup = $this->customerGroupRepository->update(request()->only([
            'code',
            'name',
        ]), $id);

        Event::dispatch('customer.customer_group.update.after', $customerGroup);

        return new JsonResponse([
            'message' => trans('admin::app.customers.groups.index.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $customerGroup = $this->customerGroupRepository->findOrFail($id);

        if (! $customerGroup->is_user_defined) {
            return new JsonResponse([
                'message' => trans('admin::app.customers.groups.index.edit.group-default'),
            ], 400);
        }

        if ($customerGroup->customers->count()) {
            return new JsonResponse([
                'message' => trans('admin::app.customers.groups.customer-associate'),
            ], 400);
        }

        try {
            Event::dispatch('customer.customer_group.delete.before', $id);

            $this->customerGroupRepository->delete($id);

            Event::dispatch('customer.customer_group.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.customers.groups.index.edit.delete-success'),
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.groups.index.edit.delete-failed'),
        ], 500);
    }
}
