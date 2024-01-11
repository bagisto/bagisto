<?php

namespace Webkul\DataTransfer\Repositories;

use Webkul\DataTransfer\Contracts\Import;
use Webkul\Core\Eloquent\Repository;

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
