<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class InstallManager
{
    /**
     * Run final commands.
     *
     * @return string
     */
    public function install()
    {
        $outputLog = new BufferedOutput;

        $this->generateKey($outputLog);
    
        return $outputLog->fetch();
    }

    /**
     * Generate New Application Key.
     *
     * @param \Symfony\Component\Console\Output\BufferedOutput $outputLog
     * @return \Symfony\Component\Console\Output\BufferedOutput|array
     */
    private static function generateKey(BufferedOutput $outputLog)
    {
        try {
            if (config('installer.final.key')) {
                Artisan::call('key:generate', ['--force'=> true], $outputLog);
            }
        } catch (Exception $e) {
            // return static::response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }
}