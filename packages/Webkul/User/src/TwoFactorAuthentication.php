<?php

namespace Webkul\User;

use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;
use Webkul\User\Contracts\Admin;

class TwoFactorAuthentication
{
    /**
     * Create a new instance.
     */
    public function __construct(protected Google2FA $google2fa) {}

    /**
     * Generate or retrieve 2FA secret for admin.
     */
    public function getOrGenerateSecret(Admin $admin): string
    {
        if (! $admin->two_factor_secret) {
            $secret = $this->google2fa->generateSecretKey();

            $admin->update([
                'two_factor_secret' => encrypt($secret),
            ]);

            return $secret;
        }

        return decrypt($admin->two_factor_secret);
    }

    /**
     * Generate QR code data for 2FA setup.
     */
    public function generateQrCodeData(Admin $admin, string $secret): array
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            $secret
        );

        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->generate($qrCodeUrl);

        $qrCodeSvg = $this->cleanSvgString((string) $qrCodeSvg);

        return [
            'qrCodeSvg' => $qrCodeSvg,
            'qrCodeUrl' => $qrCodeUrl,
        ];
    }

    /**
     * Generate complete setup data (secret + QR code) for 2FA.
     */
    public function generateSetupData(Admin $admin): array
    {
        $secret = $this->getOrGenerateSecret($admin);

        return $this->generateQrCodeData($admin, $secret);
    }

    /**
     * Enable 2FA for admin after code verification.
     */
    public function enable(Admin $admin, string $code): bool
    {
        if (! $this->verifyQrCode($admin, $code)) {
            return false;
        }

        $admin->forceFill([
            'two_factor_enabled'     => true,
            'two_factor_verified_at' => now(),
        ])->save();

        $this->sendBackupCodesEmail($admin);

        return true;
    }

    /**
     * Disable 2FA for admin.
     */
    public function disable(Admin $admin): bool
    {
        return $admin->update([
            'two_factor_secret'       => null,
            'two_factor_enabled'      => false,
            'two_factor_backup_codes' => null,
            'two_factor_verified_at'  => null,
        ]);
    }

    /**
     * Verify 2FA code or backup code.
     */
    public function verifyCode(Admin $admin, string $code): bool
    {
        if ($this->verifyQrCode($admin, $code)) {
            return true;
        }

        return $this->verifyBackupCode($admin, $code);
    }

    /**
     * Verify backup code and remove it from available codes.
     */
    public function verifyBackupCode(Admin $admin, string $code): bool
    {
        $backupCodes = $admin->two_factor_backup_codes ?? [];

        foreach ($backupCodes as $index => $storedCode) {
            if (hash_equals($storedCode, $code)) {
                unset($backupCodes[$index]);

                return $admin->update([
                    'two_factor_backup_codes' => array_values($backupCodes),
                ]);
            }
        }

        return false;
    }

    /**
     * Send backup codes via email.
     */
    protected function sendBackupCodesEmail(Admin $admin): void
    {
        try {
            $backupCodes = $this->generateBackupCodes($admin);

            Mail::to($admin->email)->send(
                new BackupCodesNotification($admin, $backupCodes)
            );
        } catch (\Exception $e) {
            \Log::error(trans('admin::app.account.messages.email-failed'), [
                'admin_id'    => $admin->id ?? null,
                'admin_email' => $admin->email ?? null,
                'exception'   => $e->getMessage(),
            ]);

            session()->flash('error', trans('admin::app.account.messages.email-failed'));
        }
    }

    /**
     * Clean SVG string from invalid characters.
     */
    protected function cleanSvgString(string $svg): string
    {
        $svg = mb_convert_encoding($svg, 'UTF-8', 'UTF-8');

        return str_replace(["\0", "\x00"], '', $svg);
    }

    /**
     * Verify a given 2FA code against the stored secret.
     */
    public function verifyQrCode(Admin $admin, string $code): bool
    {
        if (! $admin->two_factor_secret) {
            return false;
        }

        try {
            $decryptedSecret = decrypt($admin->two_factor_secret);

            return $this->google2fa->verifyKey($decryptedSecret, $code);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate and store backup codes for 2FA.
     *
     * @param  int  $count  Number of codes to generate (default: 8)
     */
    public function generateBackupCodes(Admin $admin, int $count = 8): array
    {
        $backupCodes = collect(range(1, $count))
            ->map(fn () => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT))
            ->toArray();

        $admin->update([
            'two_factor_backup_codes' => $backupCodes,
        ]);

        return $backupCodes;
    }
}
