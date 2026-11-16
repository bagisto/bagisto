<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Contracts\Country;
use Webkul\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Country::class;
    }

    /**
     * Get the countries as options of a configuration field.
     */
    public function getConfigOptions(): array
    {
        return core()->countries()
            ->map(fn ($country) => [
                'title' => $country->name,
                'value' => $country->code,
            ])
            ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }
}
