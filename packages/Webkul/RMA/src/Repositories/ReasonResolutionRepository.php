<?php

namespace Webkul\RMA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\ReasonResolution;

class ReasonResolutionRepository extends Repository
{
    /**
     * Specify model class name
     */
    public function model(): string
    {
        return ReasonResolution::class;
    }
}