<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Rules\VatIdRule;
use Webkul\Admin\DataGrids\AddressDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;

class AddressController extends Controller
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
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerAddressRepository $customerAddressRepository
    )
    {
        $this->_config = request('_config');
    }

    /**
     * Fetch address by customer id.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index($id)
    {
        $customer = $this->customerRepository->find($id);

        if (request()->ajax()) {
            return app(AddressDataGrid::class)->toJson();
        }

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $customer = $this->customerRepository->find($id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->merge([
            'address1' => implode(PHP_EOL, array_filter(request()->input('address1'))),
        ]);

        $this->validate(request(), [
            'company_name' => 'string',
            'address1'     => 'string|required',
            'country'      => 'string|required',
            'state'        => 'string|required',
            'city'         => 'string|required',
            'postcode'     => 'required',
            'phone'        => 'required',
            'vat_id'       => new VatIdRule(),
        ]);

        Event::dispatch('customer.addresses.create.before');

        $customerAddress = $this->customerAddressRepository->create(request()->all());

        Event::dispatch('customer.addresses.create.after', $customerAddress);
        
        session()->flash('success', trans('admin::app.customers.addresses.success-create'));

        return redirect()->route('admin.customer.edit', ['id' => request('customer_id')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $address = $this->customerAddressRepository->find($id);

        return view($this->_config['view'], compact('address'));
    }

    /**
     * Edit's the pre made resource of customer called address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        $this->validate(request(), [
            'company_name' => 'string',
            'address1'     => 'string|required',
            'country'      => 'string|required',
            'state'        => 'string|required',
            'city'         => 'string|required',
            'postcode'     => 'required',
            'phone'        => 'required',
            'vat_id'       => new VatIdRule(),
        ]);

        Event::dispatch('customer.addresses.update.before', $id);

        $customerAddress = $this->customerAddressRepository->update(request()->all(), $id);

        Event::dispatch('customer.addresses.update.after', $customerAddress);

        session()->flash('success', trans('admin::app.customers.addresses.success-update'));

        return redirect()->route('admin.customer.addresses.index', ['id' => $customerAddress->customer_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::dispatch('customer.addresses.delete.before', $id);

        $this->customerAddressRepository->delete($id);

        Event::dispatch('customer.addresses.delete.after', $id);

        return response()->json([
            'redirect' => false,
            'message'  => trans('admin::app.customers.addresses.success-delete')
        ]);
    }

    /**
     * Mass delete the customer's addresses.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy($id)
    {
        $addressIds = explode(',', request()->input('indexes'));

        foreach ($addressIds as $addressId) {
            Event::dispatch('customer.addresses.delete.before', $addressId);

            $this->customerAddressRepository->delete($addressId);

            Event::dispatch('customer.addresses.delete.after', $addressId);
        }

        session()->flash('success', trans('admin::app.customers.addresses.success-mass-delete'));

        return redirect()->route($this->_config['redirect'], ['id' => $id]);
    }
}
