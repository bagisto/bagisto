<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Repositories\RoleRepository;
use Webkul\User\Repositories\AdminRepository;
use Webkul\Admin\DataGrids\Settings\RolesDataGrid;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected RoleRepository $roleRepository,
        protected AdminRepository $adminRepository
    ) {
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

        return view('admin::users.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::users.roles.create');
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

        Event::dispatch('user.role.create.before');

        $data = request()->only([
            "name",
            "description",
            "permission_type",
            "permissions"
        ]);

        $role = $this->roleRepository->create($data);

        Event::dispatch('user.role.create.after', $role);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Role']));

        return redirect()->route('admin.roles.index');
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

        return view('admin::users.roles.edit', compact('role'));
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

        /**
         * Check for other admins if the role has been changed from all to custom.
         */
        $isChangedFromAll = request('permission_type') == 'custom' && $this->roleRepository->find($id)->permission_type == 'all';

        if (
            $isChangedFromAll
            && $this->adminRepository->countAdminsWithAllAccess() === 1
        ) {
            session()->flash('error', trans('admin::app.response.being-used', ['name' => 'Role', 'source' => 'Admin User']));

            return redirect()->route('admin.roles.index');
        }

        $data = array_merge(request()->only([
            "name",
            "description",
            "permission_type",
        ]), [
            'permissions' => request()->has('permissions') ? request('permissions') : [],
        ]);

        Event::dispatch('user.role.update.before', $id);

        $role = $this->roleRepository->update($data, $id);

        Event::dispatch('user.role.update.after', $role);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Role']));

        return redirect()->route('admin.roles.index');
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
            Event::dispatch('user.role.delete.before', $id);

            $this->roleRepository->delete($id);

            Event::dispatch('user.role.delete.after', $id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Role'])]);
        } catch (\Exception $e) {
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Role'])], 500);
    }
}
