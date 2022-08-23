<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\DataGrids\CustomerDataGrid;
use Webkul\Admin\DataGrids\CustomerOrderDataGrid;
use Webkul\Admin\DataGrids\CustomersInvoicesDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\NewCustomerNotification;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerGroupRepository $customerGroupRepository
    )
    {
        $this->_config = request('_config');
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

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $groups = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        return view($this->_config['view'], compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today',
        ]);

        $password = rand(100000, 10000000);

        Event::dispatch('customer.registration.before');

        $customer = $this->customerRepository->create(array_merge(request()->all() , [
            'password'    => bcrypt($password),
            'is_verified' => 1,
        ]));

        Event::dispatch('customer.registration.after', $customer);

        try {
            $configKey = 'emails.general.notifications.emails.general.notifications.customer';

            if (core()->getConfigData($configKey)) {
                Mail::queue(new NewCustomerNotification($customer, $password));
            }
        } catch (\Exception $e) {
            report($e);
        }

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Customer']));

        return redirect()->route($this->_config['redirect']);
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

        return view($this->_config['view'], compact('customer', 'groups'));
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
        ]);

        Event::dispatch('customer.update.before', $id);
        
        $customer = $this->customerRepository->update(array_merge(request()->all(), [
            'status'       => request()->has('status'),
            'is_suspended' => request()->has('is_suspended'),
        ]), $id);

        Event::dispatch('customer.update.after', $customer);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Customer']));

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
        $customer = $this->customerRepository->findorFail($id);

        try {
            if (! $this->customerRepository->checkIfCustomerHasOrderPendingOrProcessing($customer)) {
                $this->customerRepository->delete($id);

                return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Customer'])]);
            }

            return response()->json(['message' => trans('admin::app.response.order-pending', ['name' => 'Customer'])], 400);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Customer'])], 400);
    }

    /**
     * To load the note taking screen for the customers.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function createNote($id)
    {
        $customer = $this->customerRepository->find($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    /**
     * To store the response of the note in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeNote()
    {
        $this->validate(request(), [
            'notes' => 'string|nullable',
        ]);

        Event::dispatch('customer.update.before', request()->input('_customer'));

        $customer = $this->customerRepository->update([
            'notes' => request()->input('notes'),
        ], request()->input('_customer'));

        Event::dispatch('customer.update.after', $customer);

        session()->flash('success', 'Note taken');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To mass update the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $customerIds = explode(',', request()->input('indexes'));

        $updateOption = request()->input('update-options');

        foreach ($customerIds as $customerId) {
            Event::dispatch('customer.update.before', $customerId);

            $customer = $this->customerRepository->update([
                'status' => $updateOption,
            ], $customerId);

            Event::dispatch('customer.update.after', $customer);
        }

        session()->flash('success', trans('admin::app.customers.customers.mass-update-success'));

        return redirect()->back();
    }

    /**
     * To mass delete the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $customerIds = explode(',', request()->input('indexes'));

        if (! $this->customerRepository->checkBulkCustomerIfTheyHaveOrderPendingOrProcessing($customerIds)) {
            foreach ($customerIds as $customerId) {
                Event::dispatch('customer.delete.before', $customerId);
                
                $this->customerRepository->delete($customerId);

                Event::dispatch('customer.delete.after', $customerId);
            }

            session()->flash('success', trans('admin::app.customers.customers.mass-destroy-success'));

            return redirect()->back();
        }

        session()->flash('error', trans('admin::app.response.order-pending', ['name' => 'Customers']));

        return redirect()->back();
    }

    /**
     * Retrieve all invoices from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomersInvoicesDataGrid
     */
    public function invoices($id)
    {
        if (request()->ajax()) {
            return app(CustomersInvoicesDataGrid::class)->toJson();
        }
    }

    /**
     * Retrieve all orders from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomerOrderDataGrid
     */
    public function orders($id)
    {
        if (request()->ajax()) {
            return app(CustomerOrderDataGrid::class)->toJson();
        }

        $customer = $this->customerRepository->find(request('id'));

        return view($this->_config['view'], compact('customer'));
    }
}
