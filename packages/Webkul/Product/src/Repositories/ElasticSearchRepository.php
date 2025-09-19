<?php

namespace Webkul\Product\Repositories;

use Webkul\Attribute\Enums\AttributeTypeEnum;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketing\Repositories\SearchSynonymRepository;
use Webkul\Product\Helpers\Product;

class ElasticSearchRepository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected SearchSynonymRepository $searchSynonymRepository
    ) {}

    /**
     * Return elastic search index name.
     */
    public function getIndexName(): string
    {
        return Product::formatElasticSearchIndexName(
            core()->getRequestedChannelCode(),
            core()->getRequestedLocaleCodeInRequestedChannel()
        );
    }

    /**
     * Return product ids from elasticsearch.
     */
    public function search(array $params, array $options): array
    {
        $filters = $this->getFilters($params);

        if (! empty($params['category_id'])) {
            $filters['filter'][]['term']['category_ids'] = $params['category_id'];
        }

        if (! empty($params['type'])) {
            $filters['filter'][]['term']['type'] = $params['type'];
        }

        $results = Elasticsearch::search([
            'index' => $params['index'] ?? $this->getIndexName(),
            'body'  => [
                'from'          => $options['from'],
                'size'          => $options['limit'],
                'stored_fields' => [],
                'query'         => [
                    'bool' => $filters ?: new \stdClass,
                ],
                'sort'          => $this->getSortOptions($options),
            ],
        ]);

        return [
            'total' => $results['hits']['total']['value'],
            'ids'   => collect($results['hits']['hits'])->pluck('_id')->toArray(),
        ];
    }

    /**
     * Get suggestions based on the query text.
     */
    public function getSuggestions(?string $queryText): ?string
    {
        if (empty($queryText)) {
            return null;
        }

        $results = Elasticsearch::search([
            'index' => $this->getIndexName(),
            'body'  => [
                'suggest' => [
                    'name_suggest' => [
                        'text' => $queryText,
                        'term' => [
                            'field'        => 'name',
                            'suggest_mode' => 'always',
                        ],
                    ],
                ],
                'size' => 1,
            ],
        ]);

        return $results['suggest']['name_suggest'][0]['options'][0]['text'] ?? null;
    }

    /**
     * Prepare filters for search results.
     */
    public function getFilters(array $params): array
    {
        if (! empty($params['query'])) {
            $params['name'] = $params['query'];
        }

        $filterableAttributes = $this->attributeRepository
            ->getProductDefaultAttributes(array_keys($params));

        $filters = [];

        foreach ($filterableAttributes as $attribute) {
            $filter = $this->getFilterValue($attribute, $params);

            if (
                $attribute->is_configurable
                || $attribute->is_filterable
            ) {
                $filters['must'][]['bool']['should'] = $filter;
            } else {
                $filters['filter'][] = $filter;
            }
        }

        return $filters;
    }

    /**
     * Return applied filters.
     */
    public function getFilterValue(mixed $attribute, array $params): array
    {
        switch ($attribute->type) {
            case AttributeTypeEnum::BOOLEAN->value:
                $values = array_map('intval', explode(',', $params[$attribute->code]));

                $values = array_map('intval', explode(',', $params[$attribute->code]));

                return [
                    'terms' => [
                        $attribute->code => $values,
                    ],
                ];

            case AttributeTypeEnum::PRICE->value:
                $customerGroup = $this->customerRepository->getCurrentGroup();

                $range = explode(',', $params[$attribute->code]);

                return [
                    'range' => [
                        $attribute->code.'_'.$customerGroup->id => [
                            'gte' => core()->convertToBasePrice(current($range)),
                            'lte' => core()->convertToBasePrice(end($range)),
                        ],
                    ],
                ];

            case AttributeTypeEnum::TEXT->value:
                $synonyms = $this->searchSynonymRepository->getSynonymsByQuery($params[$attribute->code]);

                $synonyms = array_map(function ($synonym) {
                    return '"'.$synonym.'"';
                }, $synonyms);

                return [
                    'query_string' => [
                        'query'         => implode(' OR ', $synonyms),
                        'default_field' => $attribute->code,
                    ],
                ];

            case AttributeTypeEnum::SELECT->value:
                $filter[]['terms'][$attribute->code] = explode(',', $params[$attribute->code]);

                if ($attribute->is_configurable) {
                    $filter[]['terms']['ca_'.$attribute->code] = explode(',', $params[$attribute->code]);
                }

                return $filter;

            case AttributeTypeEnum::CHECKBOX->value:
            case AttributeTypeEnum::MULTISELECT->value:
                $values = explode(',', $params[$attribute->code]);

                $filter[]['terms'][$attribute->code] = $values;

                return $filter;

            default:
                throw new \InvalidArgumentException(
                    'Unsupported attribute type: '.$attribute->type
                );
        }
    }

    /**
     * Returns sort options.
     */
    public function getSortOptions(array $options): array
    {
        if ($options['order'] == 'rand') {
            return [
                '_script' => [
                    'type'   => 'number',
                    'script' => 'Math.random()',
                    'order'  => 'asc',
                ],
            ];
        }

        $sort = $options['sort'];

        if ($options['sort'] == 'price') {
            $customerGroup = $this->customerRepository->getCurrentGroup();

            $sort = 'price_'.$customerGroup->id;
        }

        if ($options['sort'] == 'name') {
            $sort .= '.keyword';
        }

        return [
            $sort => [
                'order' => $options['order'],
            ],
        ];
    }

    /**
     * Get product maximum price from the product indexes.
     */
    public function getMaxPrice(array $params = [])
    {
        $filters = $this->getFilters($params);

        if (! empty($params['category_id'])) {
            $filters['filter'][]['term']['category_ids'] = $params['category_id'];
        }

        if (! empty($params['type'])) {
            $filters['filter'][]['term']['type'] = $params['type'];
        }

        $customerGroupId = $this->customerRepository->getCurrentGroup()->id;

        $results = Elasticsearch::search([
            'index'         => $params['index'] ?? $this->getIndexName(),
            'body'          => [
                'size'  => 0,
                'query' => [
                    'bool' => $filters ?: new \stdClass,
                ],
                'aggs' => [
                    'max_price' => [
                        'max' => [
                            'field' => 'price_'.$customerGroupId,
                        ],
                    ],
                ],
            ],
        ]);

        return $results['aggregations']['max_price']['value'] ?? 0;
    }

    /**
     * Get product minimum price from the product indexes.
     */
    public function getMinPrice(array $params = [])
    {
        $filters = $this->getFilters($params);

        if (! empty($params['category_id'])) {
            $filters['filter'][]['term']['category_ids'] = $params['category_id'];
        }

        if (! empty($params['type'])) {
            $filters['filter'][]['term']['type'] = $params['type'];
        }

        $customerGroupId = $this->customerRepository->getCurrentGroup()->id;

        $results = Elasticsearch::search([
            'index'         => $params['index'] ?? $this->getIndexName(),
            'body'          => [
                'size'  => 0,
                'query' => [
                    'bool' => $filters ?: new \stdClass,
                ],
                'aggs' => [
                    'min_price' => [
                        'min' => [
                            'field' => 'price_'.$customerGroupId,
                        ],
                    ],
                ],
            ],
        ]);

        return $results['aggregations']['min_price']['value'] ?? 0;
    }
}
