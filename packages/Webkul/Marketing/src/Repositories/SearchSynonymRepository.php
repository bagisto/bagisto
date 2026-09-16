<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketing\Contracts\SearchSynonym;

class SearchSynonymRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return SearchSynonym::class;
    }

    /**
     * Returns synonyms by query.
     *
     * @param  string  $query
     * @return array
     */
    public function getSynonymsByQuery($query)
    {
        $synonyms = [$query];

        $searchSynonyms = $this->whereRaw(db_grammar()->findInSet('?', 'terms'), $synonyms)->get();

        foreach ($searchSynonyms as $searchSynonym) {
            $synonyms = array_merge($synonyms, explode(',', $searchSynonym->terms));
        }

        return array_unique($synonyms);
    }
}
