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
     * @return void
     */
    public function store(ForgotPasswordRequest $request)
    {
        $request->validated();

        try {
            $response = $this->broker()->sendResetLink($request->only(['email']));

            $flashMessage = match ($response) {
                Password::RESET_LINK_SENT => 'success',
                Password::RESET_THROTTLED => 'warning',
                default => null,
            };

            if ($flashMessage) {
                session()->flash($flashMessage, trans('shop::app.customers.forgot-password.' . ($flashMessage === 'success' ? 'reset-link-sent' : 'already-sent')));
            } else {
                return back()->withInput($request->only(['email']))->withErrors([
                    'email' => trans('shop::app.customers.forgot-password.email-not-exist'),
                ]);
            }
        } catch (\Swift_RfcComplianceException $e) {
            session()->flash('success', trans('shop::app.customers.forgot-password.reset-link-sent'));
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans($e->getMessage()));
        }

        return back();
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
