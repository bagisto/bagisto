<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Contracts\CustomerNote;

class CustomerNoteRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CustomerNote::class;
    }
}
