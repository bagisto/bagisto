<?php

namespace Webkul\Installer\Events;

use Illuminate\Support\Facades\Event;
use Symfony\Component\Console\Output\ConsoleOutput;

class ComposerEvents
{
    /**
     * @return void
     */
    public static function postCreateProject()
    {
        Event::dispatch('installer.installed');

        $output = new ConsoleOutput();

        $output->writeln(file_get_contents(__DIR__ . '/../Templates/on-boarding.php'));
    }
}