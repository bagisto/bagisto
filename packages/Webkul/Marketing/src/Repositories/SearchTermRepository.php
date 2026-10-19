<?php

namespace Webkul\Marketing\Repositories;

use Illuminate\Contracts\Database\Query\Expression;
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
     * Record one use of a search term in the single statement the database resolves against the
     * unique index, so two shoppers searching the same new term cannot both create it.
     */
    public function recordSearch(array $attributes): void
    {
        $this->getModel()->newQuery()->upsert(
            [[
                'term' => $attributes['term'],
                'channel_id' => $attributes['channel_id'],
                'locale' => $attributes['locale'],
                'results' => (int) $attributes['results'],
                'uses' => 1,
            ]],
            ['term', 'channel_id', 'locale'],
            [
                'uses' => $this->incrementedUses(),
                'results',
            ]
        );
    }

    /**
     * The stored use count plus one, qualified by its table because PostgreSQL reads a bare column
     * in an upsert as ambiguous between the stored row and the one being inserted.
     */
    protected function incrementedUses(): Expression
    {
        $model = $this->getModel();

        return DB::raw($model->getConnection()->getQueryGrammar()->wrap($model->qualifyColumn('uses')).' + 1');
    }
}
