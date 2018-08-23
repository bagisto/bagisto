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

        $this->middleware('auth:customer');
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
        $address = collect($this->address->findOneWhere(['id'=>$id]));

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

        if(count($address)==0)
            return view($this->_config['view'])->with('address', 'You don\'t have any addresses saved yet, create new.');
        else
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

        $this->validate(request(), [

            'address1' => 'string|required',
            'address1' => 'string|required',
            'country' => 'string|required',
            'state' => 'string|required',
            'city' => 'string|required',
            'pincode' => 'numeric|required',

        ]);

        $data = collect(request()->input())->except('_token')->toArray();
        dd($data);

    }

    /**
     * For editing the
     * existing address
     * of the customer
     *
     * @return View
     */
    public function edit() {

        return view($this->_config['view']);

    }
}
