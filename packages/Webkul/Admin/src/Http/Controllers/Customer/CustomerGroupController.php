<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerGroupRepository as CustomerGroup;

/**
 * CustomerGroup controlller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerGroupController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
    */
    protected $_config;

    /**
     * CustomerGroupRepository object
     *
     * @var array
    */
    protected $customerGroup;

     /**
     * Create a new controller instance.
     *
     * @param Webkul\Customer\Repositories\CustomerGroupRepository as customerGroup;
     * @return void
     */
    public function __construct(CustomerGroup $customerGroup)
    {
        $this->_config = request('_config');

        $this->middleware('admin');

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
        return view($this->_config['view']);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'string|required',
        ]);

        $data = request()->all();

        $data['is_user_defined'] = 1;

        $this->customerGroup->create($data);

        session()->flash('success', trans('admin::app.customers.customers.group-created'));

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
        $group = $this->customerGroup->findOneWhere(['id'=>$id]);

        return view($this->_config['view'],compact('group'));
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
            'name' => 'string|required',
        ]);

        $this->customerGroup->update(request()->all(),$id);

        session()->flash('success', trans('admin::app.customers.customers.group-updated'));

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
        $group = $this->customerGroup->findOneByField('id', $id);

        if ($group->is_user_defined == 0) {
            session()->flash('warning', trans('admin::app.customers.customers.group-default'));
        } else {
            session()->flash('success', trans('admin::app.customers.customers.group-deleted'));

            $this->customerGroup->delete($id);
        }

        return redirect()->back();
    }
}