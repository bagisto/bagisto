<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\SearchTerm;

class SearchTermRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return SearchTerm::class;
    }
}
