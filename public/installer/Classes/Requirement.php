<?php

class Requirement {

    /**
     * Check for the server requirements.
     *
     * @return array
     */
    public function checkRequirements(): array
    {
        // Server Requirements
        $requirements =  [
            'php' => [
                'calendar',
                'ctype',
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
            // 'apache' => [
            //     'mod_rewrite',
            // ]
        ];

        $results = [];

        foreach($requirements as $type => $requirement)
        {
            switch ($type) {
                // check php requirements
                case 'php':
                    foreach($requirements[$type] as $requirement)
                    {
                        $results['requirements'][$type][$requirement] = true;

                        if(!extension_loaded($requirement))
                        {
                            $results['requirements'][$type][$requirement] = false;

                            $results['errors'] = true;
                        }
                    }
                break;

                // check apache requirements
                // case 'apache':
                //     foreach ($requirements[$type] as $requirement) {
                //         // if function doesn't exist we can't check apache modules
                //         if(function_exists('apache_get_modules'))
                //         {
                //             $results['requirements'][$type][$requirement] = true;

                //             if(!in_array($requirement,apache_get_modules()))
                //             {
                //                 $results['requirements'][$type][$requirement] = false;

                //                 $results['errors'] = true;
                //             }
                //         }
                //     }
                //break;
            }
        }

        return $results;
    }

    /**
     * Check PHP version requirement.
     *
     * @return array
     */
    public function checkPHPVersion(): array
    {
        /**
         * Minimum PHP Version Supported (Override is in installer.php config file).
         *
         * @var string _minPhpVersion
         */
        $_minPhpVersion = '8.1';

        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported = false;

        if (version_compare((str_pad($currentPhpVersion['version'], 6, "0")), $_minPhpVersion) >= 0) {
            $supported = true;
        }

        return [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $_minPhpVersion,
            'supported' => $supported
        ];
    }

    /**
     * Get current Php version information
     *
     * @return array
     */
    private static function getPhpVersionInfo(): array
    {
        $currentVersionFull = PHP_VERSION;
        preg_match("#^\d+(\.\d+)*#", $currentVersionFull, $filtered);
        $currentVersion = $filtered[0];

        return [
            'full' => $currentVersionFull,
            'version' => $currentVersion
        ];
    }

    /**
     * Check composer installation.
     *
     * @return array
     */
    public function composerInstall(): array
    {
        $location = str_replace('\\', '/', getcwd());
        $currentLocation = explode("/", $location);
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $autoLoadFile = $desiredLocation . '/' . 'vendor' . '/' . 'autoload.php';

        if (file_exists($autoLoadFile)) {
            $data['composer_install'] = 0;
        } else {
            $data['composer_install'] = 1;
            $data['composer'] = 'Bagisto has detected that the required composer dependencies are not installed.<br />Go to the root directory of Bagisto and run "composer install".';
        }

        return $data;
    }

    /**
     * Render view for class.
     *
     */
    public function render()
    {
        $requirements = $this->checkRequirements();

        $phpVersion = $this->checkPHPversion();

        $composerInstall = $this->composerInstall();

        ob_start();

        include __DIR__ . '/../Views/requirements.blade.php';

        return ob_get_clean();
    }
}
