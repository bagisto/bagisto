<?php

namespace Webkul\Product\Repositories;

use Elasticsearch;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Webkul\Attribute\Repositories\AttributeRepository;

class ElasticSearchRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(protected AttributeRepository $attributeRepository)
    {
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

        $filters = $this->addFilters();

        $params = [
            'index' => $this->getIndexName(),
            'body'  => [
                'from' => $from,
                'stored_fields' => [],
                'query' => [
                    'bool' => [
                        'filter' => $filters,
                    ]
                ],
                'sort' => [
                    $options['sort'] . '.keyword' => [
                        'order' => $options['order'],
                    ],
                ],
            ],
        ];

        dd($params);

        $results = Elasticsearch::search($params);

        dd($results);

        return [
            'total' => $results['hits']['total']['value'],
            'ids'   => collect($results['hits']['hits'])->pluck('_id')->toArray()
        ];
    }
    
    /**
     * Refresh product indices
     *
     * @return void
     */
    public function getIndexName()
    {
        return 'products_' . core()->getRequestedChannelCode() . '_' . core()->getRequestedLocaleCode() . '_index';
    }
    
    /**
     * Refresh product indices
     *
     * @return void
     */
    public function addFilters()
    {
        $params = request()->input();

        $filterableAttributes = $this->attributeRepository
            ->getProductDefaultAttributes(array_keys($params));

        $filters = [];

        foreach ($filterableAttributes as $attribute) {
            switch ($attribute->type) {
                case 'price':
                    $range = explode(',', $params[$attribute->code]);

                    // $filters['range'][$attribute->code] = [
                    //     'gte' => core()->convertToBasePrice(current($range)),
                    //     'lte' => core()->convertToBasePrice(end($range)),
                    // ];

                    break;
                
                case 'text':
                    $filters['match_phrase_prefix'][$attribute->code] = $params[$attribute->code];

                    break;
                
                case 'select':
                    $filters['term'][$attribute->code] = $params[$attribute->code];

                    break;
            }
        }

        return $filters;
    }
}
