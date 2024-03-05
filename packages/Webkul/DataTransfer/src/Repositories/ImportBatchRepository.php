<?php

namespace Webkul\DataTransfer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\DataTransfer\Contracts\ImportBatch;

class ImportBatchRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ImportBatch::class;
    }
}
