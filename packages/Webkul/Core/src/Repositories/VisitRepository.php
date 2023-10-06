<?php

namespace Webkul\Core\Repositories;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;

class VisitRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Core\Contracts\Visit';
    }

    /**
     * Calculate sale amount by date.
     */
    public function getTotalCountByDate(?Carbon $from = null, ?Carbon $to = null): ?int
    {
        if ($from && $to) {
            return $this->whereNull('visitable_id')
                ->whereBetween('created_at', [$from, $to])
                ->count();
        }

        if ($from) {
            return $this->whereNull('visitable_id')
                ->where('created_at', '>=', $from)
                ->count();
        }

        if ($to) {
            return $this->whereNull('visitable_id')
                ->where('created_at', '<=', $to)
                ->count();
        }

        return $this->model->whereNull('visitable_id')->count();
    }

    /**
     * Calculate sale amount by date.
     */
    public function getTotalUniqueCountByDate(?Carbon $from = null, ?Carbon $to = null): ?int
    {
        if ($from && $to) {
            return $this->whereNull('visitable_id')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
                ->get()
                ->count();
        }

        if ($from) {
            return $this->whereNull('visitable_id')
                ->where('created_at', '>=', $from)
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
                ->get()
                ->count();
        }

        if ($to) {
            return $this->whereNull('visitable_id')
                ->where('created_at', '<=', $to)
                ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
                ->get()
                ->count();
        }

        return $this->whereNull('visitable_id')
            ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'))
            ->get()
            ->count();
    }
}