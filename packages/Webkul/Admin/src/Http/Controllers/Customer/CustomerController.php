<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;

/**
 * Customer controlller for the customer
 * to show customer data on admin login
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerRepository object
     *
     * @var array
     */
    protected $customer;

     /**
     * CustomerGroupRepository object
     *
     * @var array
     */
    protected $customerGroup;

     /**
     * Create a new controller instance.
     *
     * @param Webkul\Customer\Repositories\CustomerRepository as customer;
     * @param Webkul\Customer\Repositories\CustomerGroupRepository as customerGroup;
     * @return void
     */
    public function __construct(Customer $customer , CustomerGroup $customerGroup )
    {
        $this->_config = request('_config');

        $this->customer = $customer;

        $this->customerGroup = $customerGroup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view($this->_config['view']);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customerGroup = $this->customerGroup->all();

        return view($this->_config['view'], compact('customerGroup'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'phone' => 'nullable|numeric',
            'email' => 'required|unique:customers,email'
        ]);

        $data=$request->all();

        $password = bcrypt(rand(100000,10000000));

        $data['password']=$password;

        $this->customer->create($data);

        session()->flash('success', 'Customer created successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = $this->customer->findOneWhere(['id'=>$id]);

        $customerGroup = $this->customerGroup->all();

        return view($this->_config['view'],compact('customer','customerGroup'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'phone' => 'nullable|numeric',
            'email' => 'required|unique:customers,email,'. $id
        ]);

        $this->customer->update(request()->all(),$id);

        session()->flash('success', 'Customer updated successfully.');

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
        $this->customer->delete($id);

        session()->flash('success', 'Customer deleted successfully.');

        return redirect()->back();
    }
}
