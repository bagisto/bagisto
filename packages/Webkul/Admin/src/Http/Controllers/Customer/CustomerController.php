<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;
use Webkul\Core\Repositories\ChannelRepository as Channel;

use Webkul\Admin\DataGrids\CustomerDataGrid as CustomerDataGrid;
use Webkul\Admin\Exports\DataGridExport;
use Excel;

/**
 * Customer controlller
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
     * ChannelRepository object
     *
     * @var array
     */
    protected $channel;

     /**
     * CustomerDataGrid object
     *
     * @var array
     */
    protected $customerDataGrid;

     /**
     * Create a new controller instance.
     *
     * @param Webkul\Customer\Repositories\CustomerRepository as customer;
     * @param Webkul\Customer\Repositories\CustomerGroupRepository as customerGroup;
     * @param Webkul\Core\Repositories\ChannelRepository as Channel;
     * @param Webkul\Admin\DataGrids\CustomerDataGrid as customerDataGrid;
     * @return void
     */
    public function __construct(Customer $customer, CustomerGroup $customerGroup, Channel $channel, CustomerDataGrid $customerDataGrid)
    {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->customer = $customer;

        $this->customerGroup = $customerGroup;

        $this->channel = $channel;

        $this->customerDataGrid = $customerDataGrid;

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

        $channelName = $this->channel->all();

        return view($this->_config['view'],compact('customerGroup','channelName'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'channel_id' => 'required',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'phone' => 'nullable|numeric|unique:customers,phone',
            'email' => 'required|unique:customers,email'
        ]);

        $data=request()->all();

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

        $channelName = $this->channel->all();

        return view($this->_config['view'],compact('customer', 'customerGroup', 'channelName'));
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
        $this->validate(request(), [
            'channel_id' => 'required',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'gender' => 'required',
            'phone' => 'nullable|numeric|unique:customers,phone,'. $id,
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

    /**
     * function to export datagrid
     *
    */
    public function export()
    {
        $data = $this->customerDataGrid;

        return Excel::download(new DataGridExport($data), 'customers.xlsx');
    }
}
