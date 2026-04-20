<?php

namespace Webkul\Omnibus\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Omnibus\Services\OmnibusPriceManager;

class PurgeOldSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omnibus:purge-old-snapshots
                            {--all : Delete every snapshot regardless of age}
                            {--force : Skip the confirmation prompt when using --all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Omnibus price snapshots older than the configured retention window, or every snapshot when --all is passed.';

    /**
     * Create a new command instance.
     */
    public function __construct(
        protected OmnibusPriceManager $omnibusPriceManager
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('all')) {
            if (
                ! $this->option('force')
                && ! $this->confirm(trans('omnibus::app.console.confirm-delete-all'), false)
            ) {
                $this->components->warn(trans('omnibus::app.console.aborted'));

                return;
            }

            $this->omnibusPriceManager->cleanAllRecords();

            $this->components->success(trans('omnibus::app.console.all-deleted'));

            return;
        }

        $this->omnibusPriceManager->cleanOldRecords();

        $this->components->success(trans('omnibus::app.console.old-purged'));
    }
}
