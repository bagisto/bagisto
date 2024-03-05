<?php

namespace Webkul\DataTransfer\Jobs\Import;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\DataTransfer\Helpers\Import as ImportHelper;

class LinkBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $typeImported = app(ImportHelper::class)
            ->setImport($this->importBatch->import)
            ->getTypeImporter();

        $typeImported->linkBatch($this->importBatch);
    }
}
