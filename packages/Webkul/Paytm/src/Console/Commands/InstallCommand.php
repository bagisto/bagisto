<?php

namespace Webkul\Paytm\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paytm:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Paytm payment gateway package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call('optimize:clear');

        $this->call('vendor:publish', [
            '--provider' => 'Webkul\\Paytm\\Providers\\PaytmServiceProvider',
            '--tag' => 'public',
        ]);

        $this->info(trans('paytm::app.admin.install.success'));

        return self::SUCCESS;
    }
}
