<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\UpCommand as OriginalCommand;

class UpChannelCommand extends OriginalCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'channel:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Up channel command. Same as parent but database will not update.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        parent::handle();
    }
}
