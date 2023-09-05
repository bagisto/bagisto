<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Repositories\AdminRepository;
use Webkul\User\Repositories\RoleRepository;
use Webkul\User\Http\Requests\UserForm;
use Webkul\Admin\DataGrids\Settings\UserDataGrid;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AdminRepository $adminRepository,
        protected RoleRepository $roleRepository
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(UserDataGrid::class)->toJson();
        }

        $roles = $this->roleRepository->all();

        return view('admin::settings.users.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserForm $request
     * @return JsonResource
     */
    public function store(UserForm $request): JsonResource
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
            'role_id',
            'status'
        ]);

        if ($data['password'] ?? null) {
            $data['password'] = bcrypt($data['password']);

            $data['api_token'] = Str::random(80);
        }

        Event::dispatch('user.admin.create.before');

        $admin = $this->adminRepository->create($data);

        if (request()->hasFile('image')) {
            $admin->image = current(request()->file('image'))->store('admins/' . $admin->id);

            $admin->save();
        }

        Event::dispatch('user.admin.create.after', $admin);

        return new JsonResource([
            'message' => trans('admin::app.settings.users.index.create-success'),
        ]);
    }

    /**
     * User Details
     *
     * @param int $id
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $user = $this->adminRepository->findOrFail($id);

        $roles = $this->roleRepository->all();

        return new JsonResource([
            'roles' => $roles,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserForm $request
     * @return JsonResource
     */
    public function update(UserForm $request): JsonResource
    {
        $id = request()->id;

        $data = $this->prepareUserData($request, $id);

        if ($data instanceof \Illuminate\Http\RedirectResponse) {
            return $data;
        }

        Event::dispatch('user.admin.update.before', $id);

        $admin = $this->adminRepository->update($data, $id);

        $previousImage = $admin->image;

        if (request()->hasFile('image')) {
            $admin->image = current(request()->file('image'))->store('admins/' . $admin->id);

        } else {
            $admin->image = null;

            Storage::delete((string)$previousImage);
        }

        $admin->save();

        if (!empty($data['password'])) {
            Event::dispatch('admin.password.update.after', $admin);
        }

        Event::dispatch('user.admin.update.after', $admin);

        return new JsonResource([
            'message' => trans('admin::app.settings.users.index.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResource
     */
    public function destroy($id): JsonResource
    {
        if ($this->adminRepository->count() == 1) {
            return response()->json([
                'message' => trans('admin::app.settings.users.last-delete-error'
            )], 400);
        }

        if (auth()->guard('admin')->user()->id == $id) {
            return response()->json([
                'redirect' => route('super.settings.users.confirm', ['id' => $id]),
            ]);
        }

        try {
            Event::dispatch('user.admin.delete.before', $id);

            $this->adminRepository->delete($id);

            Event::dispatch('user.admin.delete.after', $id);

            return new JsonResource([
                'message' => trans('admin::app.settings.users.delete-success')
            ]);
        } catch (\Exception $e) {
        }

        return new JsonResource([
            'message' => trans('admin::app.settings.users.delete-failed'),
        ], 500);
    }

    /**
     * Show the form for confirming the user password.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function confirm($id)
    {
        $user = $this->adminRepository->findOrFail($id);

        return view('admin::customers.customers.confirm-password', compact('user'));
    }

    /**
     * Destroy current after confirming.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroySelf()
    {
        $password = request()->input('password');

        if (Hash::check($password, auth()->guard('admin')->user()->password)) {
            if ($this->adminRepository->count() == 1) {
                session()->flash('error', trans('admin::app.settings.users.delete-last'));
            } else {
                $id = auth()->guard('admin')->user()->id;

                Event::dispatch('user.admin.delete.before', $id);

                $this->adminRepository->delete($id);

                Event::dispatch('user.admin.delete.after', $id);

                session()->flash('success', trans('admin::app.settings.users.delete-success'));

                return redirect()->route('admin.session.create');
            }
        } else {
            session()->flash('warning', trans('admin::app.settings.users.incorrect-password'));

            return redirect()->route('admin.settings.users.index');
        }
    }

    /**
     * Prepare user data.
     *
     * @param  int  $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
    private function prepareUserData(UserForm $request, $id)
    {
        $data = $request->validated();

        $user = $this->adminRepository->find($id);

        /**
         * Password check.
         */
        if (!$data['password']) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        /**
         * Is user with `permission_type` all changed status.
         */
        $data['status'] = isset($data['status']);

        $isStatusChangedToInactive = !$data['status'] && (bool) $user->status;

        if (
            $isStatusChangedToInactive
            && (auth()->guard('admin')->user()->id === (int) $id
                || $this->adminRepository->countAdminsWithAllAccessAndActiveStatus() === 1
            )
        ) {
            return $this->cannotChangeRedirectResponse('status');
        }

        /**
         * Is user with `permission_type` all role changed.
         */
        $isRoleChanged = $user->role->permission_type === 'all'
            && isset($data['role_id'])
            && (int) $data['role_id'] !== $user->role_id;

        if (
            $isRoleChanged
            && $this->adminRepository->countAdminsWithAllAccess() === 1
        ) {
            return $this->cannotChangeRedirectResponse('role');
        }

        return $data;
    }

    /**
     * Cannot change redirect response.
     */
    private function cannotChangeRedirectResponse(string $columnName): \Illuminate\Http\RedirectResponse
    {
        session()->flash('error', trans('admin::app.settings.users.cannot-change', [
            'name' => $columnName,
        ]));

        return redirect()->route('admin.settings.users.index');
    }
}
