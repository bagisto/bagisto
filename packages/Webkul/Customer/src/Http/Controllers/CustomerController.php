<?php

namespace Webkul\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Customer\Models\Customer;
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
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;
    protected $customer;


    public function __construct(CustomerRepository $customer)
    {
        $this->_config = request('_config');
        $this->customer = $customer;
        $this->middleware('auth:customer');
    }

    /**
     * For taking the customer
     * to the dashboard after
     * authentication
     * @return view
     */
    private function getCustomer($id) {
        $customer = collect($this->customer->findOneWhere(['id'=>$id]));
        return $customer;
    }

    /**
     * Taking the customer
     * to profile details
     * page
     * @return View
     */
    public function index() {
        $id = auth()->guard('customer')->user()->id;

        $customer = $this->getCustomer($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    /**
     * For loading the
     * edit form page.
     *
     * @return View
     */
    public function editIndex() {
        $id = auth()->guard('customer')->user()->id;

        $customer = $this->getCustomer($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    /**
     * Edit function
     * for editing customer
     * profile.
     *
     * @return Redirect.
     */
    public function edit() {

        $id = auth()->guard('customer')->user()->id;

        $this->validate(request(), [

            'first_name' => 'string',
            'last_name' => 'string',
            'gender' => 'required',
            'date_of_birth' => 'date',
            'phone' => 'string|size:10',
            'email' => 'email|unique:customers,email,'.$id,
            'password' => 'confirmed|required_if:oldpassword,!=,null'

        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        if($data['oldpassword'] == null) {

            $data = collect(request()->input())->except(['_token','password','password_confirmation','oldpassword'])->toArray();
            if($this->customer->update($data, $id)) {
                Session()->flash('success','Profile Updated Successfully');
                return redirect()->back();
            } else {
                Session()->flash('success','Profile Updated Successfully');
                return redirect()->back();
            }

        } else {

            $data = collect(request()->input())->except(['_token','oldpassword'])->toArray();

            $data['password'] = bcrypt($data['password']);

            if($this->customer->update($data, $id)) {
                Session()->flash('success','Profile Updated Successfully');
                return redirect()->back();
            } else {
                Session()->flash('success','Profile Updated Successfully');
                return redirect()->back();
            }
        }
    }

    public function orders() {
        return view($this->_config['view']);
    }

    public function wishlist() {
        return view($this->_config['view']);
    }

    public function reviews() {
        return view($this->_config['view']);
    }

    public function address() {
        return view($this->_config['view']);
    }
}
