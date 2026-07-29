<?php

namespace Webkul\DataTransfer\Jobs\Import;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\DataTransfer\Helpers\Import as ImportHelper;
use Webkul\DataTransfer\Jobs\Concerns\BoundedByRetryAfter;
use Webkul\DataTransfer\Jobs\Concerns\RetriesOnDeadlock;

class ImportBatch implements ShouldQueue
{
    use Batchable, BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, RetriesOnDeadlock, SerializesModels;

    /**
     * Bounded by the connection's `retry_after` so the queue can never hand this
     * batch to a second worker while this one still holds it — which would import
     * every row in it twice.
     */
    public int $timeout;

    /**
     * Create a new job instance.
     *
     * @param  mixed  $importBatch
     * @return void
     */
    public function __construct(protected $importBatch)
    {
        $this->importBatch = $importBatch;

        $this->timeout = static::timeoutWithinRetryAfter();
    }

    /**
     * Execute the job.
     *
     * Batches run side by side across the fleet and touch the same tables, so
     * InnoDB will pick one of them as the victim of a lock cycle sooner or
     * later. The tallies are zeroed at the top of each attempt because
     * importBatch() writes them into the batch summary — left running, a second
     * attempt would report the first attempt's rows on top of its own.
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
