<?php

namespace Webkul\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\User\Repositories\AdminRepository as Admin;
use Webkul\User\Repositories\RoleRepository as Role;
use Webkul\User\Http\Requests\UserForm;
use Hash;

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
     * @param  \Webkul\User\Repositories\AdminRepository $admin
     * @param  \Webkul\User\Repositories\RoleRepository $role
     * @return void
     */
    public function __construct(Admin $admin, Role $role)
    {
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

        if (isset($data['password']) && $data['password'])
            $data['password'] = bcrypt($data['password']);

        Event::fire('user.admin.create.before');

        $admin = $this->admin->create($data);

        Event::fire('user.admin.delete.after', $admin);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'User']));

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
        $user = $this->admin->findOrFail($id);

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

        if (! $data['password'])
            unset($data['password']);
        else
            $data['password'] = bcrypt($data['password']);

        if (isset($data['status'])) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        Event::fire('user.admin.update.before', $id);

        $admin = $this->admin->update($data, $id);

        Event::fire('user.admin.update.after', $admin);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'User']));

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
        if ($this->admin->count() == 1) {
            session()->flash('error', trans('admin::app.response.last-delete-error', ['name' => 'Admin']));
        } else {
            Event::fire('user.admin.delete.before', $id);

            if (auth()->guard('admin')->user()->id == $id) {
                return view('admin::customers.confirm-password');
            }

            $this->admin->findOrFail($id)->delete();

            Event::fire('user.admin.delete.after', $id);

            session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Admin source']));
        }

        return redirect()->back();
    }

    /**
     * destroy current after confirming
     *
     * @return mixed
     */
    public function destroySelf()
    {
        $password = request()->input('password');

        if (Hash::check($password, auth()->guard('admin')->user()->password)) {
            if ($this->admin->count() == 1) {
                session()->flash('error', trans('admin::app.users.users.delete-last'));
            } else {
                $id = auth()->guard('admin')->user()->id;

                Event::fire('user.admin.delete.before', $id);

                $this->admin->delete($id);

                Event::fire('user.admin.delete.after', $id);

                session()->flash('success', trans('admin::app.users.users.delete-success'));

                return redirect()->route('admin.session.create');
            }
        } else {
            session()->flash('warning', trans('admin::app.users.users.incorrect-password'));

            return redirect()->route($this->_config['redirect']);
        }
    }
}