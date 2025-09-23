<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page with QR code and secret key.
     */
    public function setup(Request $request)
    {
        try {
            $admin = auth('admin')->user();

            if (! $admin) {
                return response()->json([
                    'message' => trans('admin::app.errors.401.title'),
                ], 401);
            }

            $qrCodeData = two_factor_authentication()->generateSetupData($admin);

            return response()->json($qrCodeData);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enable 2FA after verifying code.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $admin = auth('admin')->user();

        if (two_factor_authentication()->enable($admin, $request->code)) {
            session()->put('two_factor_passed', true);
            
            session()->flash('success', trans('admin::app.account.messages.enabled-success'));
        } else {
            session()->flash('error', trans('admin::app.account.messages.invalid-code'));
        }

        return redirect()->back();
    }

    /**
     * Disable 2FA configuration.
     */
    public function disable()
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return response()->json([
                'message' => trans('admin::app.errors.401.title'),
            ], 401);
        }

        $admin->update(
            two_factor_authentication()->getDisableValues()
        );

        $message = trans('admin::app.account.messages.disabled-success');

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Show verification form for login.
     */
    public function showVerifyForm()
    {
        return view('admin::account.verify');
    }

    /**
     * Verify 2FA or backup code during login.
     */
    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $admin = auth('admin')->user();

        if (two_factor_authentication()->verifyCode($admin, $request->code)) {
            return $this->handleSuccessfulVerification();
        }

        return back()->withErrors([
            'code' => trans('admin::app.account.messages.invalid-code'),
        ]);
    }

    /**
     * Handle successful 2FA verification.
     */
    protected function handleSuccessfulVerification()
    {
        session()->put('two_factor_passed', true);

        return redirect()->intended(route('admin.dashboard.index'))
            ->with('success', trans('admin::app.account.messages.verified-success'));
    }
}
