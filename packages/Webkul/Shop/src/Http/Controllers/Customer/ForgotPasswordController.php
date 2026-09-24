<?php

namespace Webkul\Shop\Http\Controllers\Customer;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Show the form for creating a new resource.
     *
     * @return View
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
            $response = $this->broker()->sendResetLink($this->credentials($request));

            if ($response == Password::RESET_LINK_SENT) {
                session()->flash('success', trans('shop::app.customers.forgot-password.reset-link-sent'));

                return redirect()->route('shop.customers.forgot_password.create');
            }

            if ($response == Password::RESET_THROTTLED) {
                session()->flash('warning', trans('shop::app.customers.forgot-password.already-sent'));

                return redirect()->route('shop.customers.forgot_password.create');
            }

            session()->flash('success', trans('shop::app.customers.forgot-password.reset-link-sent'));

            return redirect()->route('shop.customers.forgot_password.create');
        } catch (\Swift_RfcComplianceException $e) {
            session()->flash('success', trans('shop::app.customers.forgot-password.reset-link-sent'));

            return redirect()->route('shop.customers.forgot_password.create');
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.customers.forgot_password.create');
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker('customers');
    }

    /**
     * The credentials a reset link is requested with, scoped to the current channel because a
     * customer may only sign in on the channel they registered against.
     */
    protected function credentials(Request $request): array
    {
        return [
            'email' => $request->input('email'),
            'channel_id' => core()->getCurrentChannel()->id,
        ];
    }
}
