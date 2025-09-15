<?php

namespace Webkul\TwoFactorAuth\Models\Traits;

use PragmaRX\Google2FA\Google2FA;

trait TwoFactorAuthenticatable
{
    /**
     * Generate and store a new secret key for Google2FA.
     *
     * @return string  Encrypted secret key
     */
    public function generateTwoFactorSecret(): string
    {
        $google2fa = new Google2FA;

        $this->google2fa_secret = encrypt($google2fa->generateSecretKey());

        $this->save();

        return $this->google2fa_secret;
    }

    /**
    * Verify a given 2FA code against the stored secret.
    *
    * @param  string  $code
    * @return bool
    */
    public function verifyQrCode(string $code): bool
    {
        if (! $this->google2fa_secret) {
            return false;
        }

        $google2fa = new Google2FA;

        try {
            $decryptedSecret = decrypt($this->google2fa_secret);

            $result = $google2fa->verifyKey($decryptedSecret, $code);

            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
    * Generate and store backup codes for 2FA.
    *
    * @param  int  $count  Number of codes to generate (default: 8)
    * @return array
    */
    public function generateBackupCodes(int $count = 8): array
    {
        $this->backup_codes = collect(range(1, $count))
            ->map(fn () => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT))
            ->toArray();
    
        $this->save();
    
        return $this->backup_codes;
    }
}
