<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\UpCommand as OriginalCommand;
use Webkul\Core\Models\Channel;

class UpChannelCommand extends OriginalCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'channel:up';

    public function handle()
    {
        parent::handle();
    }
}
