<?php

namespace Webkul\Core\Events;

use Symfony\Component\Console\Output\ConsoleOutput;

class ComposerEvents
{
    /**
     * @return void
     */
    public static function postCreateProject()
    {
        $output = new ConsoleOutput();

        $output->writeln(file_get_contents(__DIR__ . '/../Templates/on-boarding.php'));
    }
}