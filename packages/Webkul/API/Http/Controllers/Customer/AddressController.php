<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerAddressRepository as CustomerAddress;
use Auth;
use Cart;
use Validator;

/**
 * Address controller for the APIs Customer Address
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AddressController extends Controller
{
    protected $customer;
    protected $customerAddress;

    public function __construct(CustomerAddress $customerAddress)
    {
        $this->customerAddress = $customerAddress;

        if(auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            $this->customer = false;
        }
    }

    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function getAddress() {
        if($this->customer == false) {
            return response()->json($this->customer, 401);
        } else {
            $addresses = $this->customer->addresses;

            return response()->json($addresses, 200);
        }
    }

    public function getDefaultAddress() {
        if($this->customer == false) {
            return response()->json($this->customer, 401);
        } else {
            $defaultAddress = $this->customer->default_address;

            if ($defaultAddress->count() > 0) {
                return response()->json($defaultAddress, 200);
            } else {
                return response()->json(['false, default address not found'], 200);
            }
        }
    }

    public function createAddress() {
        $data = request()->all();

        $validator = Validator::make(request()->all(), [
            'address1' => 'string|required',
            'address2' => 'string',
            'country' => 'string|required',
            'state' => 'string|required',
            'city' => 'string|required',
            'postcode' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $cust_id['customer_id'] = $this->customer->id;
        $data = array_merge($cust_id, $data);

        if($this->customer->addresses->count() == 0) {
            $data['default_address'] = 1;
        }

        if($this->address->create($data)) {
            session()->flash('success', 'Address have been successfully added.');

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', 'Address cannot be added.');

            return redirect()->back();
        }
    }
}