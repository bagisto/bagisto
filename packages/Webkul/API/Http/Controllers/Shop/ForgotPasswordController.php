<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Webkul\Customer\Http\Requests\CustomerForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerForgotPasswordRequest $request)
    {
        $request->validated();

        $response = $this->broker()->sendResetLink($request->only(['email']));

        return $response == Password::RESET_LINK_SENT
            ? response()->json([
                'message' => trans($response),
            ])
            : response()->json([
                'error' => trans($response),
            ]);
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
