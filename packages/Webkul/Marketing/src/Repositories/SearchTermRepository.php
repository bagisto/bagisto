<?php

namespace Webkul\Marketing\Repositories;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
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

    /**
     * Record one use of a search term. The insert is attempted rather than guarded by a read,
     * so two shoppers searching the same new term cannot both create it.
     */
    public function recordSearch(array $attributes): void
    {
        $identity = [
            'term' => $attributes['term'],
            'channel_id' => $attributes['channel_id'],
            'locale' => $attributes['locale'],
        ];

        $results = (int) $attributes['results'];

        if ($this->addUse($identity, $results)) {
            return;
        }

        try {
            $this->create($identity + [
                'uses' => 1,
                'results' => $results,
            ]);
        } catch (UniqueConstraintViolationException) {
            $this->addUse($identity, $results);
        }
    }

    /**
     * Add one to the uses of an existing term, answering whether there was one to add to.
     */
    protected function addUse(array $identity, int $results): bool
    {
        return $this->getModel()
            ->newQuery()
            ->where($identity)
            ->update([
                'uses' => DB::raw('uses + 1'),
                'results' => $results,
            ]) > 0;
    }
}
