<?php

namespace Webkul\TwoFactorAuth\Http\Controllers\Admin;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Http\Controllers\Controller;
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
    public function setup()
    {
        $admin = auth('admin')->user();

        $secret = $admin->google2fa_secret ?? $admin->generateTwoFactorSecret();

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            decrypt($secret)
        );

        $this->coreConfigRepository->updateOrCreate([
            'code' => 'general.two_factor_auth.settings.enabled',
        ], [
            'value' => '0',
        ]);

        return view('two_factor_auth::admin.setup', compact('qrCodeUrl', 'secret'));
    }

    /**
    * Enable 2FA after verifying code.
    */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $admin = auth('admin')->user();

        if ($admin->verifyQrCode($request->code)) {

            $admin->two_factor_enabled = true;
            $admin->two_factor_verified_at = now();
            $admin->save();

            $backupCodes = $admin->generateBackupCodes();

            session()->put('two_factor_passed', true);

            $this->coreConfigRepository->updateOrCreate([
                'code' => 'general.two_factor_auth.settings.enabled',
            ], [
                'value' => '1',
            ]);

            session()->flash('success', trans('two_factor_auth::app.messages.enabled_success'));

            return redirect()->route('admin.configuration.index', [
                'slug'  => 'general',
                'slug2' => 'two_factor_auth',
            ]);
        }

        return back()->withErrors(['code' => trans('two_factor_auth::app.messages.invalid_code')]);
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

        $this->coreConfigRepository->updateOrCreate(
            ['code' => 'general.two_factor_auth.settings.enabled'],
            ['value' => '0']
        );

        session()->flash('success', trans('two_factor_auth::app.messages.disabled_success'));

        return redirect()->route('admin.configuration.index', [
            'slug'  => 'general',
            'slug2' => 'two_factor_auth',
        ]);
    }

    /**
     * Redirect back to 2FA config page.
     */
    public function back()
    {
        return redirect()->route('admin.configuration.index', [
            'slug'  => 'general',
            'slug2' => 'two_factor_auth',
        ]);
    }

    /**
    * Show verification form for login.
    */
    public function showVerifyForm()
    {
        return view('two_factor_auth::admin.verify');
    }

    /**
    * Verify 2FA or backup code during login.
    */
    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

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
            'code' => trans('two_factor_auth::app.messages.invalid_code'),
        ]);
    }

    /**
     * Handle successful 2FA verification.
     */
    protected function handleSuccessfulVerification()
    {
        session()->put('two_factor_passed', true);

        return redirect()->intended(route('admin.dashboard.index'))
            ->with('success', trans('two_factor_auth::app.messages.verified_success'));
    }
}
