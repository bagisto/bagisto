<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Repositories\AdminRepository;

class TwoFactorController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected AdminRepository $adminRepository) {}

    /**
     * Show 2FA setup page with QR code and secret key.
     */
    public function setup(Request $request)
    {
        try {
            $admin = auth('admin')->user();

            if (! $admin) {
                return response()->json([
                    'success' => false,
                    'message' => trans('admin::app.errors.401.title'),
                ], 401);
            }

            $secret = $this->adminRepository->getOrGenerateTwoFactorSecret($admin);
            $qrCodeData = $this->adminRepository->generateTwoFactorQrCodeData($admin, $secret);

            return response()->json([
                'success' => true,
                ...$qrCodeData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
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

        if ($this->adminRepository->enableTwoFactor($admin, $request->code)) {
            session()->put('two_factor_passed', true);
            session()->flash('success', trans('admin::app.account.messages.enabled_success'));
        } else {
            session()->flash('error', trans('admin::app.account.messages.invalid_code'));
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
                'success' => false,
                'message' => trans('admin::app.errors.401.title'),
            ], 401);
        }

        $this->adminRepository->disableTwoFactor($admin);

        $message = trans('admin::app.account.messages.disabled_success');

        return response()->json([
            'success' => true,
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

        if ($this->adminRepository->verifyTwoFactorCode($admin, $request->code)) {
            return $this->handleSuccessfulVerification();
        }

        return back()->withErrors([
            'code' => trans('admin::app.account.messages.invalid_code'),
        ]);
    }

    /**
     * Handle successful 2FA verification.
     */
    protected function handleSuccessfulVerification()
    {
        session()->put('two_factor_passed', true);

        return redirect()->intended(route('admin.dashboard.index'))
            ->with('success', trans('admin::app.account.messages.verified_success'));
    }
}
