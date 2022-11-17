<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Elasticsearch;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class ElasticSearchRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository
    )
    {
    }
    
    /**
     * Return elastic search index name
     *
     * @return void
     */
    public function getIndexName()
    {
        return 'products_' . core()->getRequestedChannelCode() . '_' . core()->getRequestedLocaleCode() . '_index';
    }

    /**
     * Returns product ids from Elasticsearch
     *
     * @param  integer  $categoryId
     * @param  array  $options
     * @return array
     */
    public function search($categoryId, $options)
    {
        $from = ($options['page'] * $options['limit']) - $options['limit'];

        $filters = $this->getFilters();

        if ($categoryId) {
            $filters['filter'][]['term']['category_ids'] = $categoryId;
        }

        $params = [
            'index' => $this->getIndexName(),
            'body'  => [
                'from'          => $from,
                'size'          => $options['limit'],
                'stored_fields' => [],
                'query'         => [
                    'bool' => $filters ?: new \stdClass(),
                ],
                'sort'          => $this->getSortOptions($options),
            ],
        ];

        $results = Elasticsearch::search($params);

        return [
            'total' => $results['hits']['total']['value'],
            'ids'   => collect($results['hits']['hits'])->pluck('_id')->toArray()
        ];
    }
    
    /**
     * Return filters
     *
     * @return void
     */
    public function getFilters()
    {
        $params = request()->input();

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
     *
     * @return void
     */
    public function getFilterValue($attribute, $params)
    {
        switch ($attribute->type) {
            case 'boolean':
                return [
                    'term' => [
                        $attribute->code => intval($params[$attribute->code]),
                    ]
                ];
            case 'price':
                $customerGroup = $this->customerRepository->getCurrentGroup();

                $range = explode(',', $params[$attribute->code]);

                return [
                    'range' => [
                        $attribute->code . '_' . $customerGroup->id => [
                            'gte' => core()->convertToBasePrice(current($range)),
                            'lte' => core()->convertToBasePrice(end($range)),
                        ],
                    ],
                ];
            
            case 'text':
                return [
                    'term' => [
                        $attribute->code => $params[$attribute->code],
                    ]
                ];
            
            case 'select':
                $filter[]['terms'][$attribute->code] = explode(',', $params[$attribute->code]);

                if ($attribute->is_configurable) {
                    $filter[]['terms']['ca_' . $attribute->code] = explode(',', $params[$attribute->code]);
                }

                return $filter;
        }
    }
    
    /**
     * Returns sort options
     *
     * @param  array  $options
     * @return array
     */
    public function getSortOptions($options)
    {
        if ($options['order'] == 'rand') {
            return [
                '_script' => [
                    'type'   => 'number',
                    'script' => [
                        'source' => '(doc[\'_id\'].value + params.salt).hashCode()',
                        'params' => [
                            'salt' => Str::random(40),
                        ],
                    ],
                    'order'  => 'asc',
                ],
            ];
        }

        $sort = $options['sort'];

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
