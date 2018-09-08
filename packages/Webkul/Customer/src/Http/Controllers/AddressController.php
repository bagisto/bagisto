<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Auth;

/**
 * Customer controlller for the customer
 * basically for the tasks of customers
 * which will be done after customer
 * authenticastion.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;
    protected $customer;
    protected $address;


    public function __construct(CustomerRepository $customer, CustomerAddressRepository $address)
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customer = $customer;

        $this->address = $address;
    }

    /**
     * Getting logged in
     * customer helper
     * @return Array
     */
    private function getCustomer($id) {

        $customer = collect($this->customer->findOneWhere(['id'=>$id]));

        return $customer;
    }

    /**
     * Getting logged in
     * customer address
     * helper
     * @return Array
     */
    private function getAddress($id) {

        $address = collect($this->address->findOneWhere(['customer_id'=>$id]));

        return $address;

    }


    /**
     * Address Route
     * index page
     * @return View
     */
    public function index() {
        $id = auth()->guard('customer')->user()->id;

        $customer = $this->getCustomer($id);

        $address = $this->getAddress($id);

        return view($this->_config['view'])->with('address', $address);
    }

    /**
     * Show the address
     * create form
     * @return View
     */
    public function show() {
        return view($this->_config['view']);
    }

    /**
     * Create a new
     * address for
     * customer.
     *
     * @return View
     */
    public function create() {

        $id = auth()->guard('customer')->user()->id;

        $data = collect(request()->input())->except('_token')->toArray();

        $this->validate(request(), [

            'address1' => 'string|required',
            'address2' => 'string|required',
            'country' => 'string|required',
            'state' => 'string|required',
            'city' => 'string|required',
            'postcode' => 'numeric|required',

        ]);

        $cust_id['customer_id'] = $id;

        $data = array_merge($cust_id, $data);

        $address = $this->getAddress($id);


        if(count($address) == 0 || $address->isEmpty()) {
            if($this->address->create($data)) {
                session()->flash('success', 'Address have been successfully added.');

                return redirect()->route($this->_config['redirect']);

            } else {
                session()->flash('error', 'Address cannot be added.');

                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Cannot create a new address due to previously existing address');

            return redirect()-route('customer.address.edit');
        }



    }

    /**
     * For editing the
     * existing address
     * of the customer
     *
     * @return View
     */
    public function showEdit() {

        $id = auth()->guard('customer')->user()->id;

        $address = $this->getAddress($id);

        return view($this->_config['view'])->with('address', $address);

    }

    public function edit() {

        $id = auth()->guard('customer')->user()->id;

        $this->validate(request(), [

            'address1' => 'string|required',
            'address2' => 'string|required',
            'country' => 'string|required',
            'state' => 'string|required',
            'city' => 'string|required',
            'postcode' => 'numeric|required',

        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        $address = $this->getAddress($id);

        if($this->address->update($data, $id)) {
            Session()->flash('success','Address Updated Successfully.');

            return redirect()->route('customer.address.index');
        } else {
            Session()->flash('success','Address Cannot be Updated.');

            return redirect()->route('customer.address.edit');
        }
    }
}
