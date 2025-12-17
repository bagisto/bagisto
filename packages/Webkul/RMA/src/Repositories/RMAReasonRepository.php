<?php

namespace Webkul\RMA\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Core\Eloquent\Repository;
use Webkul\RMA\Contracts\RMAReason;

class RMAReasonRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return RMAReason::class;
    }

    /**
     * Get RMA Reasons by Resolution Type
     */
    public function getRMAReasonsByResolutionType(string $resolutionType): Collection
    {
        $existResolutions = app(RMAReasonResolutionRepository::class)
            ->where('resolution_type', $resolutionType)
            ->pluck('rma_reason_id');

        return $this->whereIn('id', $existResolutions)
            ->where('status', 1)
            ->get();
    }
}
