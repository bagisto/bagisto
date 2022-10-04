<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Foundation\Console\DownCommand as OriginalCommand;

class DownChannelCommand extends OriginalCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channel:down {--redirect= : The path that users should be redirected to}
                                         {--render= : The view that should be pre-rendered for display during maintenance mode}
                                         {--retry= : The number of seconds after which the request may be retried}
                                         {--refresh= : The number of seconds after which the browser may refresh}
                                         {--secret= : The secret phrase that may be used to bypass maintenance mode}
                                         {--status=503 : The status code that should be used when returning the maintenance mode response}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Down channel command. Same as parent but database will not update.';

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
