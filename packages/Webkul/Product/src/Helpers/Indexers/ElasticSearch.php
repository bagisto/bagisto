<?php

namespace Webkul\Product\Helpers\Indexers;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Webkul\Attribute\Enums\AttributeTypeEnum;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Facades\ElasticSearch as ElasticSearchClient;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductRepository;

class ElasticSearch extends AbstractIndexer
{
    /**
     * Batch size.
     *
     * @var int
     */
    private $batchSize;

    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Channels.
     *
     * @var array
     */
    protected $channels;

    /**
     * Customer groups
     *
     * @var array
     */
    protected $customerGroups;

    /**
     * Product instance.
     *
     * @var \Webkul\Product\Contracts\Product
     */
    protected $product;

    /**
     * Channel instance.
     *
     * @var \Webkul\Core\Contracts\Channel
     */
    protected $channel;

    /**
     * Locale instance.
     *
     * @var \Webkul\Core\Contracts\Locale
     */
    protected $locale;

    /**
     * Create a new indexer instance.
     *
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductRepository $productRepository,
    ) {
        $this->batchSize = self::BATCH_SIZE;
    }

    /**
     * Set current product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return self
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Set Channel.
     *
     * @param  \Webkul\Core\Contracts\Channel  $channel
     * @return self
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set Locale.
     *
     * @param  \Webkul\Core\Contracts\Locale  $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Reindex every products.
     *
     * @return void
     */
    public function reindexFull()
    {
        while (true) {
            $paginator = $this->productRepository
                ->select('products.*')
                ->with([
                    'channels',
                    'categories',
                    'inventories',
                    'super_attributes',
                    'variants',
                    'variants.channels',
                    'attribute_family',
                    'attribute_values',
                    'variants.attribute_family',
                    'variants.attribute_values',
                    'price_indices',
                    'variants.price_indices',
                    'inventory_indices',
                    'variants.inventory_indices',
                ])
                ->cursorPaginate($this->batchSize);

            $this->reindexBatch($paginator->items());

            if (! $cursor = $paginator->nextCursor()) {
                break;
            }

            request()->query->add(['cursor' => $cursor->encode()]);
        }

        request()->query->remove('cursor');
    }

    /**
     * Reindex products by batch size.
     *
     * @return void
     */
    public function reindexBatch($products)
    {
        $refreshIndices = ['body' => []];

        $removeIndices = [];

        foreach ($products as $product) {
            $this->setProduct($product);

            foreach ($this->getChannels() as $channel) {
                $this->setChannel($channel);

                foreach ($channel->locales as $locale) {
                    $this->setLocale($locale);

                    $indexName = $this->getIndexName();

                    if (in_array($channel->id, $product->channels->pluck('id')->toArray())) {
                        $refreshIndices['body'][] = [
                            'index' => [
                                '_index' => $indexName,
                                '_id'    => $product->id,
                            ],
                        ];

                        $refreshIndices['body'][] = $this->getIndices();
                    } else {
                        $removeIndices[$indexName][] = $product->id;
                    }
                }
            }
        }

        if (! empty($refreshIndices['body'])) {
            ElasticsearchClient::bulk($refreshIndices);
        }

        if (! empty($removeIndices)) {
            $this->deleteIndices($removeIndices);
        }
    }

    /**
     * Delete product indices.
     *
     * @param  array  $indices
     * @return void
     */
    public function deleteIndices($indices)
    {
        foreach ($indices as $indexName => $productIds) {
            foreach ($productIds as $id) {
                $params = [
                    'index' => $indexName,
                    'id'    => $id,
                ];

                try {
                    ElasticsearchClient::delete($params);
                } catch (ClientResponseException $e) {
                }
            }
        }
    }

    /**
     * Refresh product indices.
     *
     * @return string
     */
    public function getIndexName()
    {
        return 'products_'.$this->channel->code.'_'.$this->locale->code.'_index';
    }

    /**
     * Get indices for the product.
     *
     * @return void
     */
    public function getIndices()
    {
        $properties = array_merge([
            'id'                  => $this->product->id,
            'type'                => $this->product->type,
            'sku'                 => $this->product->sku,
            'attribute_family_id' => $this->product->attribute_family_id,
            'category_ids'        => $this->product->categories->pluck('id')->toArray(),
            'created_at'          => $this->product->created_at,
        ], $this->product->additional ?? []);

        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            $attributeValue = $this->getAttributeValue($attribute);

            if ($attribute->code == AttributeTypeEnum::PRICE->value) {
                $properties[$attribute->code] = (float) $attributeValue?->{$attribute->column_name};

                foreach ($this->getCustomerGroups() as $customerGroup) {
                    if (! app()->runningInConsole()) {
                        $this->product->load('price_indices');
                    }

                    $priceIndex = $this->product->price_indices
                        ->where('channel_id', $this->channel->id)
                        ->where('customer_group_id', $customerGroup->id)
                        ->first();

                    if ($priceIndex) {
                        $groupPrice = $priceIndex?->min_price;
                    } else {
                        $groupPrice = $this->product->getTypeInstance()->getMinimalPrice();
                    }

                    $properties[$attribute->code.'_'.$customerGroup->id] = (float) $groupPrice;
                }
            } elseif ($attribute->type == AttributeTypeEnum::BOOLEAN->value) {
                $properties[$attribute->code] = intval($attributeValue?->{$attribute->column_name} ?? $attribute->default_value);
            } elseif (in_array($attribute->type, [
                AttributeTypeEnum::CHECKBOX->value,
                AttributeTypeEnum::MULTISELECT->value,
            ])) {
                $rawValue = $attributeValue?->{$attribute->column_name};

                $properties[$attribute->code] = $rawValue ? array_map('trim', explode(',', $rawValue)) : [];
            } else {
                $properties[$attribute->code] = strip_tags($attributeValue?->{$attribute->column_name});
            }
        }

        foreach ($this->product->super_attributes as $attribute) {
            foreach ($this->product->variants as $variant) {
                $properties['ca_'.$attribute->code][] = $variant->{$attribute->code};
            }
        }

        return $properties;
    }

    /**
     * Returns attributes to index.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAttributes()
    {
        if ($this->attributes) {
            return $this->attributes;
        }

        $this->attributes = $this->attributeRepository
            ->scopeQuery(function ($query) {
                return $query->where(function ($qb) {
                    return $qb
                        ->orWhereIn('code', [
                            'name',
                            'status',
                            'visible_individually',
                            'new',
                            'featured',
                            'url_key',
                            'short_description',
                            'description',
                        ])
                        ->orWhere('is_filterable', 1);
                });
            })
            ->get();

        return $this->attributes;
    }

    /**
     * Returns filterable attribute values.
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @param  \Webkul\Product\Contracts\ProductAttributeValue
     * @return void
     */
    public function getAttributeValue($attribute)
    {
        $attributeValues = $this->product->attribute_values
            ->where('attribute_id', $attribute->id);

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $attributeValues = $attributeValues
                    ->where('channel', $this->channel->code)
                    ->where('locale', $this->locale->code);
            } else {
                $attributeValues = $attributeValues->where('channel', $this->channel->code);
            }
        } else {
            if ($attribute->value_per_locale) {
                $attributeValues = $attributeValues->where('locale', $this->locale->code);
            } else {
                $attributeValues = $attributeValues;
            }
        }

        return $attributeValues->first();
    }

    /**
     * Returns all channels.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChannels()
    {
        if ($this->channels) {
            return $this->channels;
        }

        return $this->channels = $this->channelRepository->all();
    }

    /**
     * Returns all customer groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomerGroups()
    {
        if ($this->customerGroups) {
            return $this->customerGroups;
        }

        return $this->customerGroups = $this->customerGroupRepository->all();
    }
}
