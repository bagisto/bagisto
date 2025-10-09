<?php

namespace Webkul\User;

use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorAuthentication
{
    /**
     * Create a new instance.
     */
    public function __construct(protected Google2FA $google2fa) {}

    /**
     * Generate or retrieve 2FA secret for admin.
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code data for 2FA setup.
     */
    public function generateQrCode(string $email, string $secret): array
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $email,
            $secret
        );

        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->generate($qrCodeUrl);

        $qrCodeSvg = $this->cleanSvgString((string) $qrCodeSvg);

        return [
            'qrCodeSvg' => $qrCodeSvg,
            'qrCodeUrl' => $qrCodeUrl,
        ];
    }

    /**
     * Disable 2FA for admin.
     */
    public function getDisableValues(): array
    {
        return [
            'two_factor_secret'       => null,
            'two_factor_enabled'      => false,
            'two_factor_backup_codes' => null,
            'two_factor_verified_at'  => null,
        ];
    }

    /**
     * Verify backup code and return remaining codes.
     */
    public function verifyBackupCode(array $backupCodes, string $code): ?array
    {
        foreach ($backupCodes as $index => $storedCode) {
            if (hash_equals($storedCode, $code)) {
                unset($backupCodes[$index]);

                return array_values($backupCodes); // return updated codes
            }
        }

        return null;
    }

    /**
     * Verify a given 2FA code against the stored secret.
     */
    public function verifyQrCode(string $decryptedSecret, string $requestedCode): bool
    {
        try {
            return $this->google2fa->verifyKey($decryptedSecret, $requestedCode);
        } catch (\Exception $e) {
            return false;
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
     * Generate and store backup codes for 2FA.
     *
     * @param  int  $count  Number of codes to generate (default: 8)
     */
    public function generateBackupCodes(int $count = 8): array
    {
        return collect(range(1, $count))
            ->map(fn () => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT))
            ->toArray();
    }
}
