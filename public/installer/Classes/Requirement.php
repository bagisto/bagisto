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

        if (version_compare($currentPhpVersion['version'], $_minPhpVersion) >= 0) {
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
     * check installation for composer
     * @return boolean
    */
    private static function composerInstall()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = 'cd ../.. && composer --version';
        } else {
            $command = 'cd ../.. ; export HOME=/root && export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update; composer --version';
        }
        exec($command, $data['composer'], $data['composer_install']);

        return $data['composer_install'];
    }

    // /**
    //  * check installation for mysql
    //  * @return boolean
    // */
    // private static function mysqlInstall()
    // {
    //     $command = 'mysql --version';
    //     exec($command, $data['mysql'], $data['mysql_install']);
    //     $mysqlVersion = explode(",", $data['mysql'][0]);
    //     $mysqlVersion = explode(" ", $mysqlVersion[0]);
    //     $supported = false;
    //     $minMysqlVersion = '5.7.23';

    //     if ($data['mysql_install'] == 0) {
    //         if (version_compare(end($mysqlVersion), $minMysqlVersion, '>=')) {
    //             $supported = true;
    //         }
    //     }

    //     $mysqlStatus = [
    //         'current' => end($mysqlVersion),
    //         'minimum' => $minMysqlVersion,
    //         'supported' => $supported
    //     ];

    //     return $mysqlStatus;
    // }


    // /**
    //  * check installation for composer
    //  * @return boolean
    // */
    // private static function nodeInstall()
    // {
    //     $command = 'npm -v 2>&1';
    //     exec($command, $data['npm'], $data['npm_install']);

    //     return $data['npm_install'];
    // }

    /**
     * Render view for class.
     *
     */
    public function render()
    {
        $requirements = $this->checkRequirements();

        $phpVersion = $this->checkPHPversion();

        $composerInstall = $this->composerInstall();

        // $sqlInstall = $this->mysqlInstall();

        // $nodeInstall = $this->nodeInstall();

        ob_start();

        include __DIR__ . '/../Views/requirements.blade.php';

        return ob_get_clean();
    }
}
