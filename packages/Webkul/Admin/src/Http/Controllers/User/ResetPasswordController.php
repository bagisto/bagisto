<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Webkul\Admin\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($token = null)
    {
        return view('admin::users.reset-password.create')->with([
            'token' => $token,
            'email' => request('email'),
        ]);
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
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:6',
            ]);

            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']), function ($admin, $password) {
                    $this->resetPassword($admin, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                return redirect()->route('admin.dashboard.index');
            }

            return back()
                ->withInput(request(['email']))
                ->withErrors([
                    'email' => trans($response),
                ]);
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Reset the given admin's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $admin
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($admin, $password)
    {
        $admin->password = Hash::make($password);

        $admin->setRememberToken(Str::random(60));

        $admin->save();

        event(new PasswordReset($admin));

        auth()->guard('admin')->login($admin);
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
