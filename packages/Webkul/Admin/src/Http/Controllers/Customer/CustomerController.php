<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository as Customer;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;
use Webkul\Core\Repositories\ChannelRepository as Channel;

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
     * Create a new controller instance.
     *
     * @param \Webkul\Customer\Repositories\CustomerRepository $customer
     * @param \Webkul\Customer\Repositories\CustomerGroupRepository $customerGroup
     * @param \Webkul\Core\Repositories\ChannelRepository $channel
     */
    public function __construct(Customer $customer, CustomerGroup $customerGroup, Channel $channel)
    {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->customer = $customer;

        $this->customerGroup = $customerGroup;

        $this->channel = $channel;

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
        $customerGroup = $this->customerGroup->findWhere([['code', '<>', 'guest']]);

        $channelName = $this->channel->all();

        return view($this->_config['view'], compact('customerGroup','channelName'));
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
            'email' => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today'
        ]);

        $data = request()->all();

        $password = bcrypt(rand(100000,10000000));

        $data['password'] = $password;

        $data['is_verified'] = 1;

        $this->customer->create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Customer']));

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
        $customer = $this->customer->findOrFail($id);

        $customerGroup = $this->customerGroup->findWhere([['code', '<>', 'guest']]);

        $channelName = $this->channel->all();

        return view($this->_config['view'], compact('customer', 'customerGroup', 'channelName'));
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
            'email' => 'required|unique:customers,email,'. $id,
            'date_of_birth' => 'date|before:today'
        ]);

        $this->customer->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Customer']));

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
        $customer = $this->customer->findorFail($id);

        try {
            $this->customer->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Customer']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Customer']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * To load the note taking screen for the customers
     *
     * @return view
     */
    public function createNote($id)
    {
        $customer = $this->customer->find($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    /**
     * To store the response of the note in storage
     *
     * @return redirect
     */
    public function storeNote()
    {
        $this->validate(request(), [
            'notes' => 'string|nullable'
        ]);

        $customer = $this->customer->find(request()->input('_customer'));

        $noteTaken = $customer->update([
            'notes' => request()->input('notes')
        ]);

        if ($noteTaken) {
            session()->flash('success', 'Note taken');
        } else {
            session()->flash('error', 'Note cannot be taken');
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To mass update the customer
     *
     * @return redirect
     */
    public function massUpdate()
    {
        $customerIds = explode(',', request()->input('indexes'));
        $updateOption = request()->input('update-options');

        foreach ($customerIds as $customerId) {
            $customer = $this->customer->find($customerId);

            $customer->update([
                'status' => $updateOption
            ]);
        }

        session()->flash('success', trans('admin::app.customers.customers.mass-update-success'));

        return redirect()->back();
    }

    /**
     * To mass delete the customer
     *
     * @return redirect
     */
    public function massDestroy()
    {
        $customerIds = explode(',', request()->input('indexes'));

        foreach ($customerIds as $customerId) {
            $this->customer->deleteWhere([
                'id' => $customerId
            ]);
        }

        session()->flash('success', trans('admin::app.customers.customers.mass-destroy-success'));

        return redirect()->back();
    }
}