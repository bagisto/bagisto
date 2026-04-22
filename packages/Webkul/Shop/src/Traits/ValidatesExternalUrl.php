<?php

namespace Webkul\Shop\Traits;

trait ValidatesExternalUrl
{
    /**
     * Validate URL before fetching external content (SSRF guard).
     *
     * Enforces HTTP(S) only and blocks private, reserved, or loopback addresses.
     */
    protected function validateExternalUrl(string $url): bool
    {
        $parsed = parse_url($url);

        if (
            ! $parsed
            || empty($parsed['host'])
            || empty($parsed['scheme'])
        ) {
            return false;
        }

        if (! in_array($parsed['scheme'], ['http', 'https'], true)) {
            return false;
        }

        $records = dns_get_record($parsed['host'], DNS_A + DNS_AAAA);

        if (! $records) {
            return false;
        }

        foreach ($records as $record) {
            $ip = $record['ip'] ?? $record['ipv6'] ?? null;

            if (
                ! $ip
                || $this->isBlockedIp($ip)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if IP is private, reserved, or loopback.
     */
    protected function isBlockedIp(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
