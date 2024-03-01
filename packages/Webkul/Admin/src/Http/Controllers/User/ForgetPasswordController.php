<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Support\Facades\Password;
use Webkul\Admin\Http\Controllers\Controller;

class ForgetPasswordController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        } else {
            if (strpos(url()->previous(), 'admin') !== false) {
                $intendedUrl = url()->previous();
            } else {
                $intendedUrl = route('admin.dashboard.index');
            }

            session()->put('url.intended', $intendedUrl);

            return view('admin::users.forget-password.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $this->validate(request(), [
                'email' => 'required|email',
            ]);

            $response = $this->broker()->sendResetLink(
                request(['email'])
            );

            if ($response == Password::RESET_LINK_SENT) {
                session()->flash('success', trans('admin::app.users.forget-password.create.reset-link-sent'));

                return redirect()->route('admin.forget_password.create');
            }

            return redirect()->route('admin.forget_password.create')
                ->withInput(request(['email']))
                ->withErrors([
                    'email' => trans('admin::app.users.forget-password.create.email-not-exist'),
                ]);
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }
}
