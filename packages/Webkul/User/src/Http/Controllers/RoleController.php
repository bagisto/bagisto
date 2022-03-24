<?php

namespace Webkul\User\Http\Controllers;

use Webkul\Admin\DataGrids\RolesDataGrid;
use Webkul\User\Repositories\AdminRepository;
use Webkul\User\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Role repository instance.
     *
     * @var \Webkul\User\Repositories\RoleRepository
     */
    protected $roleRepository;

    /**
     * Admin repository instance.
     *
     * @var \Webkul\User\Repositories\AdminRepository
     */
    protected $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\User\Repositories\AdminRepository  $adminRepository
     * @param  \Webkul\User\Repositories\RoleRepository  $roleRepository
     * @return void
     */
    public function __construct(
        RoleRepository $roleRepository,
        AdminRepository $adminRepository
    ) {
        $this->middleware('admin');

        $this->roleRepository = $roleRepository;

        $this->adminRepository = $adminRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(RolesDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
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
            'name'            => 'required',
            'permission_type' => 'required',
        ]);

        $this->roleRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Role']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->roleRepository->findOrFail($id);

        return view($this->_config['view'], compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name'            => 'required',
            'permission_type' => 'required',
        ]);

        $params = request()->all();

        /**
         * Check for other admins if the role has been changed from all to custom.
         */
        $isChangedFromAll = $params['permission_type'] == 'custom' && $this->roleRepository->find($id)->permission_type == 'all';

        if ($isChangedFromAll && $this->adminRepository->countAdminsWithAllAccess() === 1) {
            session()->flash('error', trans('admin::app.response.being-used', ['name' => 'Role', 'source' => 'Admin User']));

            return redirect()->route($this->_config['redirect']);
        }

        $this->roleRepository->update($params, $id);

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
        $role = $this->roleRepository->findOrFail($id);

        if ($role->admins->count() >= 1) {
            return response()->json(['message' => trans('admin::app.response.being-used', ['name' => 'Role', 'source' => 'Admin User'])], 400);
        }

        if ($this->roleRepository->count() == 1) {
            return response()->json(['message' => trans('admin::app.response.last-delete-error', ['name' => 'Role'])], 400);
        }

        try {
            $this->roleRepository->delete($id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Role'])]);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Role'])], 500);
    }
}
