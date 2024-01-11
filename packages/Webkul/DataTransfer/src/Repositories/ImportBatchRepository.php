<?php

namespace Webkul\DataTransfer\Repositories;

use Webkul\DataTransfer\Contracts\ImportBatch;
use Webkul\Core\Eloquent\Repository;

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
