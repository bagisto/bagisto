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
     * 
     * @param  \Carbon\Carbon|null  $from
     * @param  \Carbon\Carbon|null  $to
     * @return int
     */
    public function getTotalCountByDate(?Carbon $from = null, ?Carbon $to = null): ?int
    {
        $qb = $this->whereNull('visitable_id');

        if ($from && $to) {
            return $qb->whereBetween('created_at', [$from, $to])->count();
        }

        if ($from) {
            return $qb->where('created_at', '>=', $from)->count();
        }

        if ($to) {
            return $qb->where('created_at', '<=', $to)->count();
        }

        return $this->model->whereNull('visitable_id')->count();
    }

    /**
     * Returns per day total count by date.
     * 
     * @param  \Carbon\Carbon|null  $from
     * @param  \Carbon\Carbon|null  $to
     * @return \Illuminate\Support\Collection
     */
    public function getPerDayTotalCountDate(?Carbon $from = null, ?Carbon $to = null)
    {
        $qb = $this->select(
                DB::raw('DATE(created_at) AS date'),
                DB::raw('COUNT(*) AS count')
            )
            ->whereNull('visitable_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'));

        if ($from && $to) {
            return $qb->whereBetween('created_at', [$from, $to])->get();
        }

        if ($from) {
            return $qb->where('created_at', '<=', $to)->get();
        }

        if ($to) {
            return $qb->where('created_at', '>=', $from)->get();
        }

        return $qb->get();
    }

    /**
     * Calculate sale amount by date.
     * 
     * @param  \Carbon\Carbon|null  $from
     * @param  \Carbon\Carbon|null  $to
     * @return int
     */
    public function getTotalUniqueCountByDate(?Carbon $from = null, ?Carbon $to = null): ?int
    {
        $qb = $this->whereNull('visitable_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('CONCAT(ip, "-", visitor_id)'));

        if ($from && $to) {
            return $qb->whereBetween('created_at', [$from, $to])->get()->count();
        }

        if ($from) {
            return $qb->whereBetween('created_at', [$from, $to])->get()->count();
        }

        if ($to) {
            return $qb->whereBetween('created_at', [$from, $to])->get()->count();
        }

        return $qb->whereBetween('created_at', [$from, $to])->get()->count();
    }
}