<?php

namespace Webkul\DataGrid\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\DataGrid\Contracts\Filter;

class FilterRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return Filter::class;
    }
}
