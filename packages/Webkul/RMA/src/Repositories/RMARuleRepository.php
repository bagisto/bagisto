<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMARule;

class RMARuleRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMARule::class;
    }

    /**
     * Get all active RMA rules.
     */
    public function getActiveRules()
    {
        return $this->model->where('status', 1)->get();
    }
}
