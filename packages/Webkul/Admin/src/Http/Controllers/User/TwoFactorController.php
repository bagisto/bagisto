<?php

namespace Webkul\Admin\Http\Controllers\User;

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
                && ! session('two_factor_passed')
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
     * Enable 2FA after verifying code.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $admin = auth('admin')->user();

        $decryptedSecret = decrypt($admin->two_factor_secret);

        $isValidCode = two_factor_authentication()->verifyQrCode($decryptedSecret, $request->code);

        if (! $isValidCode) {
            return response()->json([
                'errors' => [
                    'code' => [trans('admin::app.account.messages.invalid-code')],
                ],
            ], 422);
        }

        $admin->forceFill([
            'two_factor_enabled' => true,
            'two_factor_verified_at' => now(),
        ])->save();

        session()->put('two_factor_passed', true);

        $backupCodes = two_factor_authentication()->generateBackupCodes();

        /**
         * Persist only the hashed backup codes - the plain codes are shown once
         * on screen and emailed, but never stored in plain text.
         */
        $admin->update([
            'two_factor_backup_codes' => two_factor_authentication()->hashBackupCodes($backupCodes),
        ]);

        /**
         * The backup codes are shown on screen for the admin to download, so a
         * failed email delivery must not prevent two-factor authentication from
         * being enabled - it is only a secondary copy of the codes.
         */
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

        /**
         * A session that has two-factor authentication enabled but has not yet
         * passed verification must not be able to disable it, otherwise 2FA
         * could be bypassed by simply hitting this endpoint after logging in
         * with the password only.
         */
        if (
            $admin->two_factor_enabled
            && ! session('two_factor_passed')
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
        return view('admin::account.verify');
    }

    /**
     * Verify 2FA or backup code during login.
     */
    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $admin = auth('admin')->user();

        $decryptedSecret = decrypt($admin->two_factor_secret);

        if (two_factor_authentication()->verifyQrCode($decryptedSecret, $request->code)) {
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
     * Handle successful 2FA verification.
     */
    protected function handleSuccessfulVerification()
    {
        session()->put('two_factor_passed', true);

        return redirect()->intended(route('admin.dashboard.index'))
            ->with('success', trans('admin::app.account.messages.verified-success'));
    }
}
