<?php

class Requirement {

    /**
     * Check for the server requirements.
     *
     * @param array $requirements
     * @return array
     */
    public function checkRequirements()
    {
        // Server Requirements
        $requirements =  [
            'php' => [
                'openssl',
                'pdo',
                'mbstring',
                'tokenizer',
                'JSON',
                'cURL',
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
    public function checkPHPversion()
    {
        /**
         * Minimum PHP Version Supported (Override is in installer.php config file).
         *
         * @var _minPhpVersion
         */
        $_minPhpVersion = '7.1.17';

        $currentPhpVersion = $this->getPhpVersionInfo();
        $supported = false;

        if (version_compare((str_pad($currentPhpVersion['version'], 6, "0")), $_minPhpVersion) >= 0) {
            $supported = true;
        }

        $phpStatus = [
            'full' => $currentPhpVersion['full'],
            'current' => $currentPhpVersion['version'],
            'minimum' => $_minPhpVersion,
            'supported' => $supported
        ];

        return $phpStatus;
    }

    /**
     * Get current Php version information
     *
     * @return array
     */
    private static function getPhpVersionInfo()
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
    public function composerInstall()
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
            $data['composer'] = 'Composer dependencies is not Installed.Go to root of project, run "composer install" command to install composer dependencies & refresh page again.';
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
