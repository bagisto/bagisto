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

class LinkBatch implements ShouldQueue
{
    use Batchable, BoundedByRetryAfter, Dispatchable, InteractsWithQueue, Queueable, RetriesOnDeadlock, SerializesModels;

    /**
     * Bounded by the connection's `retry_after` so the queue can never hand this
     * batch to a second worker while this one still holds it.
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
     * @return void
     */
    public function handle()
    {
        $typeImported = app(ImportHelper::class)
            ->setImport($this->importBatch->import)
            ->getTypeImporter();

        try {
            $this->retryOnDeadlock(fn () => $typeImported->linkBatch($this->importBatch));
        } finally {
            $typeImported->releaseBatchMemory();
        }
    }
}
