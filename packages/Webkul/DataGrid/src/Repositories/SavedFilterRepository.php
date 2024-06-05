<?php

namespace Webkul\DataGrid\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\DataGrid\Contracts\SavedFilter;

class SavedFilterRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SavedFilter::class;
    }
}
