<?php

namespace Webkul\DataTransfer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\DataTransfer\Contracts\Import;

class ImportRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Import::class;
    }
}
