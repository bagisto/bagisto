<?php

namespace Webkul\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\User\Http\Requests\UserForm;

/**
 * Admin user controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UserController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AdminRepository object
     *
     * @var array
     */
    protected $admin;

    /**
     * RoleRepository object
     *
     * @var array
     */
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\User\Repositories\AdminRepository $admin
     * @param  Webkul\User\Repositories\RoleRepository $role
     * @return void
     */
    public function __construct(Admin $admin, Role $role)
    {
        $this->middleware('admin');

        $this->admin = $admin;

        $this->role = $role;

        $this->_config = request('_config');

        $this->middleware('guest', ['except' => 'destroy']);
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
        $roles = $this->role->all();

        return view($this->_config['view'], compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\User\Http\Requests\UserForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserForm $request)
    {
        $data = request()->all();
        
        if(isset($data['password']) && $data['password'])
            $data['password'] = bcrypt($data['password']);

        $this->admin->create($data);

        session()->flash('success', 'User created successfully.');

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
        $user = $this->admin->find($id);

        $roles = $this->role->all();

        return view($this->_config['view'], compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\User\Http\Requests\UserForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserForm $request, $id)
    {
        $data = request()->all();

        if(!$data['password'])
            unset($data['password']);
        else
            $data['password'] = bcrypt($data['password']);

        $this->admin->update($data, $id);

        session()->flash('success', 'User updated successfully.');

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
        if($this->admin->count() == 1) {
            session()->flash('error', 'At least one admin is required.');
        } else {
            $this->admin->delete($id);

            session()->flash('success', 'Admin source deleted successfully.');
        }

        return redirect()->back();
    }
}