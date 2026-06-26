<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SubscriptionPlan;

class SubscriptionPlanRepository extends Repository
{
    public function model(): string
    {
        return SubscriptionPlan::class;
    }

    public function active(): object
    {
        return $this->model->where('is_active', true)->orderBy('sort_order')->get();
    }

    public function findBySlug(string $slug): ?object
    {
        return $this->model->where('slug', $slug)->first();
    }
}
