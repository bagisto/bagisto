<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page with QR code and secret key.
     */
    public function setup()
    {
        try {
            $admin = auth('admin')->user();

            if (! $admin) {
                return response()->json([
                    'message' => trans('admin::app.errors.401.title'),
                ], 401);
            }

            if (
                $admin->two_factor_enabled
                && $admin->two_factor_secret
                && ! $this->hasPassedTwoFactor($admin)
            ) {
                return response()->json([
                    'message' => trans('admin::app.errors.401.title'),
                ], 401);
            }

            if (! $admin->two_factor_secret) {
                $secret = two_factor_authentication()->generateSecretKey();

                $admin->update([
                    'two_factor_secret' => encrypt($secret),
                ]);
            } else {
                $secret = decrypt($admin->two_factor_secret);
            }

            $qrCodeData = two_factor_authentication()->generateQrCode($admin->email, $secret);

            return response()->json($qrCodeData);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enable 2FA after verifying code. The backup codes are shown on screen as well as emailed, so a
     * delivery failure is reported rather than allowed to stop two factor authentication going on.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $admin = auth('admin')->user();

        $decryptedSecret = decrypt($admin->two_factor_secret);

        $window = two_factor_authentication()->verifyQrCode($decryptedSecret, $request->code, $admin->two_factor_last_used_window);

        if (! $window) {
            return response()->json([
                'errors' => [
                    'code' => [trans('admin::app.account.messages.invalid-code')],
                ],
            ], 422);
        }

        $admin->forceFill([
            'two_factor_last_used_window' => $window,
            'two_factor_enabled' => true,
            'two_factor_verified_at' => now(),
        ])->save();

        session()->put('two_factor_passed_for', $admin->id);

        $backupCodes = two_factor_authentication()->generateBackupCodes();

        $admin->update([
            'two_factor_backup_codes' => two_factor_authentication()->hashBackupCodes($backupCodes),
        ]);

        try {
            Mail::to($admin->email)->send(
                new BackupCodesNotification($admin, $backupCodes)
            );
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json([
            'message' => trans('admin::app.account.messages.enabled-success'),
            'backup_codes' => $backupCodes,
        ]);
    }

    /**
     * Disable 2FA configuration. A session signed in with the password alone may not turn it off,
     * which would otherwise bypass two factor authentication by calling this endpoint.
     */
    public function disable()
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return response()->json([
                'message' => trans('admin::app.errors.401.title'),
            ], 401);
        }

        if (
            $admin->two_factor_enabled
            && ! $this->hasPassedTwoFactor($admin)
        ) {
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
        if ($redirect = $this->pendingVerificationRedirect(auth('admin')->user())) {
            return $redirect;
        }

        return view('admin::account.verify');
    }

    /**
     * Verify 2FA or backup code during login.
     */
    public function verifyTwoFactorCode(Request $request)
    {
        $admin = auth('admin')->user();

        if ($redirect = $this->pendingVerificationRedirect($admin)) {
            return $redirect;
        }

        $request->validate(['code' => 'required|digits:6']);

        $decryptedSecret = decrypt($admin->two_factor_secret);

        $window = two_factor_authentication()->verifyQrCode($decryptedSecret, $request->code, $admin->two_factor_last_used_window);

        if ($window) {
            $admin->forceFill(['two_factor_last_used_window' => $window])->save();

            return $this->handleSuccessfulVerification();
        }

        $updatedCodes = two_factor_authentication()->verifyBackupCode(
            $admin->two_factor_backup_codes ?? [],
            $request->code
        );

        if ($updatedCodes !== null) {
            $admin->update(['two_factor_backup_codes' => $updatedCodes]);

            return $this->handleSuccessfulVerification();
        }

        return back()->withErrors([
            'code' => trans('admin::app.account.messages.invalid-code'),
        ]);
    }

    /**
     * Whether this session passed two-factor verification as the given admin.
     */
    protected function hasPassedTwoFactor($admin): bool
    {
        return (int) session('two_factor_passed_for') === (int) $admin->id;
    }

    /**
     * Where to send a caller of the verification screen that has no verification left to do.
     */
    protected function pendingVerificationRedirect($admin): ?RedirectResponse
    {
        if (! $admin) {
            return redirect()->route('admin.session.create');
        }

        if (
            ! $admin->two_factor_enabled
            || ! $admin->two_factor_secret
            || $this->hasPassedTwoFactor($admin)
        ) {
            return redirect()->route('admin.dashboard.index');
        }

        return null;
    }

    /**
     * Handle successful 2FA verification.
     */
    protected function handleSuccessfulVerification()
    {
        session()->put('two_factor_passed_for', auth()->guard('admin')->id());

        return redirect()->intended(route('admin.dashboard.index'))
            ->with('success', trans('admin::app.account.messages.verified-success'));
    }
}
