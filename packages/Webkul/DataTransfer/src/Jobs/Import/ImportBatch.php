<?php

namespace Webkul\DataTransfer\Jobs\Import;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Core\Jobs\Concerns\RetriesOnDeadlock;
use Webkul\DataTransfer\Helpers\Import as ImportHelper;

class ImportBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, RetriesOnDeadlock, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  mixed  $importBatch
     * @return void
     */
    public function __construct(protected $importBatch)
    {
        $this->importBatch = $importBatch;
    }

    /**
     * Execute the job. Batches write to the same tables side by side, so one is eventually a
     * deadlock victim, and the tallies reset per attempt because importBatch() accumulates them.
     *
     * @return void
     */
    public function handle()
    {
        $typeImported = app(ImportHelper::class)
            ->setImport($this->importBatch->import)
            ->getTypeImporter();

        try {
            $this->retryOnDeadlock(function () use ($typeImported) {
                $typeImported->resetItemCounts();

                $typeImported->importBatch($this->importBatch);
            });
        } finally {
            $typeImported->releaseBatchMemory();
        }
    }
}
