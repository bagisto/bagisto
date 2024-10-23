<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\DownCommand as BaseDownCommand;
use Webkul\Core\Models\Channel;

class DownCommand extends BaseDownCommand
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->downAllChannels();

        parent::handle();
    }

    /**
     * Update all channels.
     *
     * @return mixed
     */
    protected function downAllChannels()
    {
        $this->components->info('All channels are down.');

        return Channel::query()->update(['is_maintenance_on' => 1]);
    }
}
