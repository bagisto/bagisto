<?php

namespace Webkul\API\Http\Controllers\Customer;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Auth;
use Cart;

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
        if(auth()->guard('customer')->check()) {
            $this->customer = auth()->guard('customer')->user();
        } else {
            $this->customer = 'unauthorized';
        }
    }

    /**
     * To get the details of user to display on profile
     *
     * @return response JSON
     */
    public function getProfile() {
        if($this->customer == 'unauthorized') {
            return response()->json($this->customer, 401);
        } else {
            $customer = [
                'id' => $this->customer->id,
                'first_name' => $this->customer->first_name,
                'last_name' => $this->customer->last_name,
                'gender' => $this->customer->gender,
                'date_of_birth' => $this->customer->date_of_birth,
                'email' => $this->customer->email,
                'subscribed_to_news_letter' => $this->customer->subscribed_to_news_letter,
            ];
        }

        return response()->json($customer, 200);
    }
}