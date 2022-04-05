<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;

class BagistoPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:publish { --force : Overwrite any existing files }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the available assets';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', [
            '--tag'   => 'canvas-config',
            '--force' => $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--tag'   => 'canvas-assets',
            '--force' => true,
        ]);

        $this->info('Publishing complete.');
    }
}
