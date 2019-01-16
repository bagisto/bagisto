<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Auth;
use Cart;
use Validator;
use Hash;

/**
 * Customer controller for the APIs of Profile customer
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    protected $customer;

    public function __construct()
    {
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
     * Only accepts the id of simple or configurable product
     *
     * @return response JSON
     */
    public function getProfile()
    {
        return response()->json($this->customer, 200);
    }

    public function updateProfile($id)
    {
        $validator = Validator::make(request()->all(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'date_of_birth' => 'date',
            'email' => 'email|required|unique:customers,email,'.$id,
            'password' => 'confirmed|required_if:oldpassword,!=,null'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $data = collect(request()->all())->toArray();

        if ($data['oldpassword'] == null) {
            $data = collect(request()->input())->except([ 'oldpassword', 'password', 'password_confirmation'])->toArray();
        } else {
            if (Hash::check($data['oldpassword'], auth()->guard('customer')->user()->password)) {
                $data = collect(request()->input())->toArray();

                $data['password'] = bcrypt($data['password']);
            } else {
                return response()->json('Old password does not match', 200);
            }
        }

        $result = $this->customer->update($data);

        if ($result) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
        }
    }

    /**
     * Get all instances of cart for currently logged in customer
     *
     * @return collection Cart
     */
    public function getAllCart() {
        $carts = $this->customer->carts;

        if ($cart->count() > 0) {
            return response()->json(['message' => 'successful','items' => $cart], 200);
        } else {
            return response()->json(['message' => 'empty', 'items' => null], 200);
        }
    }

    public function getActiveCart() {
        $cart = Cart::getCart();

        if ($cart == null) {
            return response()->json(['message' => 'empty', 'items' => 'null']);
        }

        if ($cart->count() > 0 ) {
            return response()->json(['message' => 'success', 'items' => $cart]);
        } else {
            return response()->json(['message' => 'empty', 'items' => 'null']);
        }
    }

}