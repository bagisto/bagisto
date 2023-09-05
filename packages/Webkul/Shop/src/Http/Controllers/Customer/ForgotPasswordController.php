<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shop::customers.forgot-password');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ForgotPasswordRequest $request)
    {
        $request->validated();

        try {
            $response = $this->broker()->sendResetLink($request->only(['email']));

            if ($response == Password::RESET_LINK_SENT) {
                session()->flash('success', trans('customer::app.forget_password.reset_link_sent'));

                return back();
            }

            return back()
                ->withInput($request->only(['email']))
                ->withErrors([
                    'email' => trans('customer::app.forget_password.email_not_exist'),
                ]);
        } catch (\Swift_RfcComplianceException $e) {
            session()->flash('success', trans('customer::app.forget_password.reset_link_sent'));

            return redirect()->back();
        } catch (\Exception $e) {
            report($e);

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
        return Password::broker('customers');
    }
}
