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
        $this->middleware('auth:customer');

        $this->customerAddress = $customerAddress;

        if (auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            $this->customer['message'] = 'unauthorized';
            $this->unAuthorized();
        }
    }

    public function unAuthorized() {
        return response()->json($this->customer, 401);
    }

    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function get() {
        if ($this->customer == false) {
            return response()->json($this->customer, 401);
        } else {
            $addresses = $this->customer->addresses;

            return response()->json($addresses, 200);
        }
    }

    public function getDefault() {
        if ($this->customer == false) {
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

    public function create() {
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

        if ($this->customer->addresses->count() == 0) {
            $data['default_address'] = 1;
        }

        $result = $this->customerAddress->create($data);

        if ($result) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
        }
    }

    public function delete($id) {
        $result = $this->customerAddress->delete($id);

        return response()->json($result, 200);
    }

    public function makeDefault($id) {
        $defaultAddress = $this->customer->default_address;

        if ($defaultAddress != null && $defaultAddress->count() > 0) {
            $defaultAddress->update(['default_address' => 0]);
        }

        if ($this->customerAddress->find($id)->default_address == 1) {
            return response()->json(false, 200);
        }

        $result = $this->customerAddress->update(['default_address' => 1], $id);

        return response()->json($result, 200);
    }
}