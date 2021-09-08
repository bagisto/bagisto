<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\UpCommand as OriginalCommand;
use Webkul\Core\Models\Channel;

class UpCommand extends OriginalCommand
{
    public function handle()
    {
        $this->upAllChannels();

        parent::handle();
    }

    protected function upAllChannels()
    {
        $this->comment('Activating all channels.');

        return Channel::query()->update(['is_maintenance_on' => 0]);
    }
}
