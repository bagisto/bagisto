<?php

namespace Webkul\Installer\Helpers;

class ServerRequirements
{
    /**
     * Minimum PHP version required for the application.
     *
     * @var string
     */
    const MIN_PHP_VERSION = '8.3.0';

    /**
     * Check for the server requirements.
     */
    public function validate(): array
    {
        $requirements = [
            'php' => [
                'calendar',
                'ctype',
                'curl',
                'dom',
                'fileinfo',
                'filter',
                'gd',
                'hash',
                'intl',
                'json',
                'mbstring',
                'openssl',
                'pcre',
                'pdo',
                'session',
                'tokenizer',
                'xml',
            ],
        ];

        $results = [];

        foreach ($requirements as $type => $requirement) {
            foreach ($requirement as $item) {
                $results['requirements'][$type][$item] = true;

                if (! extension_loaded($item)) {
                    $results['requirements'][$type][$item] = false;

                    $results['errors'] = true;
                }
            }
        }

        return $results;
    }

    /**
     * Check PHP version requirement.
     */
    public function checkPHPversion(): array
    {
        $minVersionPhp = self::MIN_PHP_VERSION;

        $currentPhpVersion = $this->getPhpVersionInfo();

        $supported = version_compare($currentPhpVersion['version'], $minVersionPhp) >= 0;

        return [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $minVersionPhp,
            'supported' => $supported,
        ];
    }

    /**
     * Get current Php version information.
     */
    public function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;

        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);

        return [
            'full' => $currentVersionFull,
            'version' => $filtered[0] ?? $currentVersionFull,
        ];
    }
}
