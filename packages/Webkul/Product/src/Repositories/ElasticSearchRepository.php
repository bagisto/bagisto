<?php

namespace Webkul\Product\Repositories;

use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketing\Repositories\SearchSynonymRepository;

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
     * Return elastic search index name
     */
    public function getIndexName(): string
    {
        return 'products_'.core()->getRequestedChannelCode().'_'.core()->getRequestedLocaleCode().'_index';
    }

    /**
     * Returns product ids from Elasticsearch
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
     * Prepare filters for search results
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
     * Return applied filters
     */
    public function getFilterValue(mixed $attribute, array $params): array
    {
        switch ($attribute->type) {
            case 'boolean':
                /**
                 * Need to remove this condition after the next release.
                 *
                 * Previously, these attributes were not indexed in Elasticsearch.
                 * Therefore, we need to check if the attributes exist in the index
                 * to maintain backward compatibility.
                 */
                if (in_array($attribute->code, ['status', 'visible_individually'])) {
                    return [
                        'bool' => [
                            'should' => [
                                [
                                    'term' => [
                                        $attribute->code => 1,
                                    ],
                                ], [
                                    'bool' => [
                                        'must_not' => [
                                            'exists' => [
                                                'field' => $attribute->code,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];
                }

                return [
                    'term' => [
                        $attribute->code => intval($params[$attribute->code]),
                    ],
                ];

            case 'price':
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

            case 'text':
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

            case 'select':
                $filter[]['terms'][$attribute->code] = explode(',', $params[$attribute->code]);

                if ($attribute->is_configurable) {
                    $filter[]['terms']['ca_'.$attribute->code] = explode(',', $params[$attribute->code]);
                }

                return $filter;
        }
    }

    /**
     * Returns sort options
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
}
