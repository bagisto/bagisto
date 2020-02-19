<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Webkul\Customer\Rules\VatIdRule;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\Customer\Repositories\CustomerAddressRepository as CustomerAddress;

/**
 * Customer's Address controller
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Customer Repository object
     *
     * @var object
     */
    protected $customer;

    /**
     * CustomerAddress Repository object
     *
     * @var object
     */
    protected $customerAddress;

    /**
     * Create a new controller instance.
     *
     * @param Webkul\Customer\Repositories\CustomerAddressRepository $customerAddress
     *
     * @return void
     */
    public function __construct(
        Customer $customer,
        CustomerAddress $customerAddress
    )
    {
        $this->customer = $customer;

        $this->customerAddress = $customerAddress;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the seller order page which will be populated.
     *
     * @return Mixed
     */
    public function index($id)
    {
        $customer = $this->customer->find($id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $customer = $this->customer->find($id);

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

        $data = collect(request()->input())->except('_token')->toArray();

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

        if ($this->customerAddress->create($data)) {
            session()->flash('success', trans('admin::app.customers.addresses.success-create'));

            return redirect()->route('admin.customer.addresses.index', ['id' => $data['customer_id']]);
        } else {
            session()->flash('success', trans('admin::app.customers.addresses.error-create'));

            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Mixed
     */
    public function edit($id)
    {
        $address = $this->customerAddress->find($id);

        return view($this->_config['view'], compact('address'));
    }

    /**
     * Edit's the premade resource of customer called
     * Address.
     *
     * @return redirect
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

        $data = collect(request()->input())->except('_token')->toArray();

        $address = $this->customerAddress->find($id);

        if ($address) {
            $this->customerAddress->update($data, $id);

            session()->flash('success', trans('admin::app.customers.addresses.success-update'));

            return redirect()->route('admin.customer.addresses.index', ['id' => $address->customer_id]);
        }
        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customerAddress->delete($id);

        session()->flash('success', trans('admin::app.customers.addresses.success-delete'));

        return redirect()->back();
    }

    /**
     * Mass Delete the customer's addresses
     *
     * @return response
     */
    public function massDestroy($id)
    {
        $addressIds = explode(',', request()->input('indexes'));

        foreach ($addressIds as $addressId) {
            $this->customerAddress->delete($addressId);
        }

        session()->flash('success', trans('admin::app.customers.addresses.success-mass-delete'));

        return redirect()->route($this->_config['redirect'], ['id' => $id]);
    }
}