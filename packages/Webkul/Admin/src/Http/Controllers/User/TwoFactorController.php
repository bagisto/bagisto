<?php

namespace Webkul\Admin\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\Admin\BackupCodesMail;
use Webkul\Core\Repositories\CoreConfigRepository;

class TwoFactorController extends Controller
{
    /**
     * Inject CoreConfigRepository dependency.
     */
    public function __construct(
        protected CoreConfigRepository $coreConfigRepository,
        protected Google2FA $google2fa,
    ) {}

    /**
     * Show 2FA setup page with QR code and secret key.
     */
    public function setup(Request $request)
    {
        try {

            $admin = auth('admin')->user();

            if (! $admin->google2fa_secret) {
                $secret = $this->google2fa->generateSecretKey();
                $admin->google2fa_secret = encrypt($secret);
                $admin->save();
            } else {
                $secret = decrypt($admin->google2fa_secret);
            }

            $qrCodeUrl = $this->google2fa->getQRCodeUrl(
                config('app.name'),
                $admin->email,
                $secret
            );

            $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($qrCodeUrl);
            $qrCodeSvg = (string) $qrCodeSvg;
            $qrCodeSvg = mb_convert_encoding($qrCodeSvg, 'UTF-8', 'UTF-8');
            $qrCodeSvg = str_replace(["\0", "\x00"], '', $qrCodeSvg);

            return response()->json([
                'success'   => true,
                'qrCodeSvg' => $qrCodeSvg,
                'qrCodeUrl' => $qrCodeUrl,
            ], 200);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error'   => $e->getMessage(),
                ], 500);
            }

            throw $e;
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

        if ($admin->verifyQrCode($request->code)) {

            $admin->two_factor_enabled = true;
            $admin->two_factor_verified_at = now();
            $admin->save();

            $backupCodes = $admin->generateBackupCodes();

            try {
                Mail::to($admin->email)->send(new BackupCodesMail($admin, $backupCodes));
            } catch (\Exception $e) {
                \Log::error(trans('admin::app.account.messages.email_failed'), [
                    'admin_id'    => $admin->id ?? null,
                    'admin_email' => $admin->email ?? null,
                    'exception'   => $e->getMessage(),
                ]);

                session()->flash('error', trans('admin::app.account.messages.email_failed'));
            }

            session()->put('two_factor_passed', true);

            session()->flash('success', trans('admin::app.account.messages.enabled_success'));

            return redirect()->back();
        }
        session()->flash('error', trans('admin::app.account.messages.invalid_code'));

        return redirect()->back();
    }

    /**
     * Disable and remove 2FA configuration.
     */
    public function remove()
    {
        $admin = auth('admin')->user();

        $admin->fill([
            'google2fa_secret'       => null,
            'two_factor_enabled'     => false,
            'backup_codes'           => null,
            'two_factor_verified_at' => null,
        ])->save();

        session()->flash('success', trans('admin::app.account.messages.disabled_success'));

        return redirect()->back();
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

        if ($admin->verifyQrCode($request->code)) {
            return $this->handleSuccessfulVerification();
        }

        $backupCodes = $admin->backup_codes ?? [];

        if (in_array($request->code, $backupCodes)) {
            $admin->backup_codes = array_values(array_diff($backupCodes, [$request->code]));
            $admin->save();

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
