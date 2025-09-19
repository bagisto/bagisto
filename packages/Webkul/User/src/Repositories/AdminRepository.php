<?php

namespace Webkul\User\Repositories;

use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use Webkul\Admin\Mail\Admin\BackupCodesNotification;
use Webkul\Core\Eloquent\Repository;
use Webkul\User\Contracts\Admin;

class AdminRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\User\Contracts\Admin';
    }

    /**
     * Count admins with all access.
     */
    public function countAdminsWithAllAccess(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }

    /**
     * Count admins with all access and active status.
     */
    public function countAdminsWithAllAccessAndActiveStatus(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->where('admins.status', 1)
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }

    /**
     * Generate or retrieve 2FA secret for admin.
     */
    public function getOrGenerateTwoFactorSecret(Admin $admin): string
    {
        if (! $admin->two_factor_secret) {
            $secret = app(Google2FA::class)->generateSecretKey();

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
    public function generateTwoFactorQrCodeData(Admin $admin, string $secret): array
    {
        $qrCodeUrl = app(Google2FA::class)->getQRCodeUrl(
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
     * Enable 2FA for admin after code verification.
     */
    public function enableTwoFactor(Admin $admin, string $code): bool
    {
        if (! $admin->verifyQrCode($code)) {
            return false;
        }

        $admin->update([
            'two_factor_enabled'     => true,
            'two_factor_verified_at' => now(),
        ]);

        $this->sendTwoFactorBackupCodesEmail($admin);

        return true;
    }

    /**
     * Disable 2FA for admin.
     */
    public function disableTwoFactor(Admin $admin): bool
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
    public function verifyTwoFactorCode(Admin $admin, string $code): bool
    {
        if ($admin->verifyQrCode($code)) {
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

        if (! in_array($code, $backupCodes)) {
            return false;
        }

        $updatedBackupCodes = array_values(array_diff($backupCodes, [$code]));

        return $admin->update([
            'two_factor_backup_codes' => $updatedBackupCodes,
        ]);
    }

    /**
     * Send backup codes via email.
     */
    protected function sendTwoFactorBackupCodesEmail(Admin $admin): void
    {
        try {
            $backupCodes = $admin->generateBackupCodes();

            Mail::to($admin->email)->send(
                new BackupCodesNotification($admin, $backupCodes)
            );
        } catch (\Exception $e) {
            \Log::error(trans('admin::app.account.messages.email_failed'), [
                'admin_id'    => $admin->id ?? null,
                'admin_email' => $admin->email ?? null,
                'exception'   => $e->getMessage(),
            ]);

            session()->flash('error', trans('admin::app.account.messages.email_failed'));
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
     * Count admins with two-factor authentication enabled.
     */
    public function countAdminsWithTwoFactorEnabled(): int
    {
        return $this->getModel()
            ->where('two_factor_enabled', true)
            ->count();
    }

    /**
     * Get admins who have two-factor enabled but not verified.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdminsWithUnverifiedTwoFactor()
    {
        return $this->getModel()
            ->where('two_factor_enabled', true)
            ->whereNull('two_factor_verified_at')
            ->get();
    }
}
