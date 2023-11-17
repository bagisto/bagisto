<?php

namespace Webkul\Marketing\Repositories;

use Webkul\Core\Eloquent\Repository;

class SearchSynonymRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Marketing\Contracts\SearchSynonym';
    }

    /**
     * Returns synonyms by query
     *
     * @param  string  $query
     * @return array
     */
    public function getSynonymsByQuery($query)
    {
        $synonyms = [$query];

        $searchSynonyms = $this->whereRaw('FIND_IN_SET(?, terms)', $synonyms)->get();

        foreach ($searchSynonyms as $searchSynonym) {
            $synonyms = array_merge($synonyms, explode(',', $searchSynonym->terms));
        }

        return array_unique($synonyms);
    }
}
