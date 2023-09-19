<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Admin\DataGrids\Customers\CustomerDataGrid;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Customer\Repositories\CustomerNoteRepository;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected CustomerNoteRepository $customerNoteRepository
    )
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
            return app(CustomerDataGrid::class)->toJson();
        }

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.customers.index', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone',
        ]);

        $password = rand(100000, 10000000);

        Event::dispatch('customer.registration.before');

        $data = array_merge(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
        ]), [
            'password'    => bcrypt($password),
            'is_verified' => 1,
        ]);

        $this->customerRepository->create($data);

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.index.create.create-success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.customers.edit', compact('customer', 'groups'));
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
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email,' . $id,
            'date_of_birth' => 'date|before:today',
            'phone'         => 'unique:customers,phone,' . $id,
        ]);

        Event::dispatch('customer.update.before', $id);

        $data = array_merge(request()->only([
            'first_name',
            'last_name',
            'gender',
            'email',
            'date_of_birth',
            'phone',
            'customer_group_id',
        ]), [
            'status'       => request()->has('status'),
            'is_suspended' => request()->has('is_suspended'),
        ]);

        $customer = $this->customerRepository->update($data, $id);

        Event::dispatch('customer.update.after', $customer);

        session()->flash('success', trans('admin::app.customers.customers.update-success'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = $this->customerRepository->findorFail($id);

        if (! $customer) {
            return response()->json(['message' => trans('admin::app.customers.customers.delete-failed')], 400);
        }

        if (! $this->customerRepository->checkIfCustomerHasOrderPendingOrProcessing($customer)) {

            $this->customerRepository->delete($id);

            session()->flash('success', trans('admin::app.customers.customers.delete-success'));

            return redirect(route('admin.customers.customers.index'));
        }

        session()->flash('success', trans('admin::app.customers.customers.view.order-pending'));

        return redirect()->back();
    }

    /**
     * Login as customer
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login_as_customer($id)
    {
        $customer = $this->customerRepository->findOrFail($id);

        auth()->guard('customer')->login($customer);

        session()->flash('success', trans('admin::app.customers.customers.index.login-message', ['customer_name' => $customer->name]));

        return redirect(route('shop.customers.account.profile.index'));
    }

    /**
     * To store the response of the note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\View
     */
    public function storeNotes($id)
    {
        $this->validate(request(), [
            'note' => 'string|required',
        ]);

        $customerNote = $this->customerNoteRepository->create([
            'customer_id'       => $id,
            'note'              => request()->input('note'),
            'customer_notified' => request()->input('customer_notified', 0),
        ]);

        if (request()->has('customer_notified')) {
            Event::dispatch('customer.note-created.after', $customerNote);
        }

        session()->flash('success', trans('admin::app.customers.customers.view.note-created-success'));

        return redirect()->back();
    }

    /**
     * View all details of customer.
     *
     * @param  int  $id
     * @return
     */
    public function show($id)
    {
        $customer = $this->customerRepository->with([
            'orders',
            'orders.addresses',
            'invoices',
            'reviews',
            'notes',
            'addresses'
        ])->findOrFail($id);

        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view('admin::customers.customers.view', compact('customer', 'groups'));
    }

    /**
     * Result of search customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $customers = $this->customerRepository->scopeQuery(function ($query) {
            return $query->where('email', 'like', '%' . urldecode(request()->input('query')) . '%')
                ->orWhere(DB::raw('CONCAT(' . DB::getTablePrefix() . 'first_name, " ", ' . DB::getTablePrefix() . 'last_name)'), 'like', '%' . urldecode(request()->input('query')) . '%')
                ->orderBy('created_at', 'desc');
        })->paginate(10);

        return response()->json($customers);
    }

    /**
     * To mass update the customer.
     *
     * @param MassUpdateRequest $massUpdateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $selectedCustomerIds = $massUpdateRequest->input('indices');
    
        foreach ($selectedCustomerIds as $customerId) {
            Event::dispatch('customer.update.before', $customerId);

            $customer = $this->customerRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $customerId);

            Event::dispatch('customer.update.after', $customer);
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.customers.index.datagrid.update-success')
        ]);
    }

    /**
     * To mass delete the customer.
     *
     * @param MassDestroyRequest $massDestroyRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $customerIds = $massDestroyRequest->input('indices');

        if (! $this->customerRepository->checkBulkCustomerIfTheyHaveOrderPendingOrProcessing($customerIds)) {
            try {
                foreach ($customerIds as $customerId) {
                    Event::dispatch('customer.delete.before', $customerId);
    
                    $this->customerRepository->delete($customerId);
    
                    Event::dispatch('customer.delete.after', $customerId);
                }
    
                return new JsonResponse([
                    'message' => trans('admin::app.customers.customers.index.datagrid.delete-success')
                ]);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage()
                ]);
            }
        }

        throw new \Exception(trans('admin::app.customers.customers.index.datagrid.order-pending'));
    }
}
