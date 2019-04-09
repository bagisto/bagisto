<?php

namespace Webkul\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\User\Repositories\RoleRepository as Role;

/**
 * Admin user role controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RoleController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * RoleRepository object
     *
     * @var array
     */
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\User\Repositories\RoleRepository $role
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->middleware('admin');

        $this->role = $role;

        $this->_config = request('_config');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'permission_type' => 'required',
        ]);

        Event::fire('user.role.create.before');

        $role = $this->role->create(request()->all());

        Event::fire('user.role.create.after', $role);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Role']));

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
        $role = $this->role->find($id);

        return view($this->_config['view'], compact('role'));
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
            'name' => 'required',
            'permission_type' => 'required',
        ]);

        Event::fire('user.role.update.before', $id);

        $role = $this->role->update(request()->all(), $id);

        Event::fire('user.role.update.after', $role);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Role']));

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
        $role = $this->role->findOrFail($id);

        if ($role->admins->count() >= 1) {
            session()->flash('error', trans('admin::app.response.being-used', ['name' => 'Role', 'source' => 'Admin']));
        } else if($this->role->count() == 1) {
            session()->flash('error', trans('admin::app.response.last-delete-error', ['name' => 'Role']));
        } else {
            try {
                Event::fire('user.role.delete.before', $id);

                $role->delete($id);

                Event::fire('user.role.delete.after', $id);

                session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Role']));

                return 'true';
            } catch(\Exception $e) {
                session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Role']));
            }
        }

        return 'false';
    }
}