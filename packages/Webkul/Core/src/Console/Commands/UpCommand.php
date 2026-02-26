<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\UpCommand as BaseUpCommand;
use Webkul\Core\Models\Channel;

class UpCommand extends BaseUpCommand
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->upAllChannels();

        parent::handle();
    }

    /**
     * Update all channels.
     *
     * @return mixed
     */
    protected function upAllChannels()
    {
        $this->components->info('Activating all channels.');

        return Channel::query()->update(['is_maintenance_on' => 0]);
    }
}
