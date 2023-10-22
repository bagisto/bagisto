<?php

namespace Webkul\Installer\Http\Helpers;

class ServerRequirements
{
    /**
     * Minimum PHP Version Supported (Override is in installer.php config file).
     *
     * @var string
     */
    private $minPhpVersion = '8.1.0';

    /**
     * Check for the server requirements.
     *
     * @return array
     */
    public function validate(): array
    {
        // Server Requirements
        $requirements = [
            'php' => [
                'Calendar',
                'cType',
                'cURL',
                'dom',
                'fileinfo',
                'filter',
                'gd',
                'hash',
                'intl',
                'JSON',
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
     *
     * @return array
     */
    public function checkPHPversion(string $minPhpVersion = null)
    {
        $minVersionPhp = $minPhpVersion ?? $this->minPhpVersion;

        $currentPhpVersion = $this->getPhpVersionInfo();

        $supported = version_compare($currentPhpVersion['version'], $minVersionPhp) >= 0;

        return [
            'full'      => $currentPhpVersion['full'],
            'current'   => $currentPhpVersion['version'],
            'minimum'   => $minVersionPhp,
            'supported' => $supported,
        ];
    }

    /**
     * Get current Php version information.
     *
     * @return array
     */
    private static function getPhpVersionInfo()
    {
        $currentVersionFull = PHP_VERSION;

        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);

        return [
            'full'    => $currentVersionFull,
            'version' => $filtered[0] ?? $currentVersionFull,
        ];
    }
}
