<?php

namespace Webkul\Product\Helpers\Indexers\ElasticSearch;

use Illuminate\Support\Arr;
use Elasticsearch;
use Webkul\Attribute\Repositories\AttributeRepository;

class Product
{
    /**
     * Create a new indexer instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @return void
     */
    public function __construct(protected AttributeRepository $attributeRepository)
    {
    }

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
     * Set current product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Helpers\Indexers\ElasticSearch\Product
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Set Channel
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Helpers\Indexers\ElasticSearch\Product
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set Locale
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Helpers\Indexers\ElasticSearch\Product
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Refresh product indices
     *
     * @return void
     */
    public function refresh()
    {
        if (
            ! $this->product->status
            || ! $this->product->visible_individually
        ) {
            return $this->delete();
        }

        $params = [
            'index' => $this->getIndexName(),
            'id'    => $this->product->id,
            'body' => $this->getElasticProperties(),
        ];

        Elasticsearch::index($params);
    }

    /**
     * Delete product indices
     *
     * @return void
     */
    public function delete()
    {
        $params = [
            'index' => $this->getIndexName(),
            'id'    => $this->product->id,
        ];

        try {
            \Elasticsearch::delete($params);
        } catch(\Exception $e) {}

        return;
    }

    /**
     * Refresh product indices
     *
     * @return void
     */
    public function getIndexName()
    {
        return 'products_' . $this->channel->code . '_' . $this->locale->code . '_index';
    }

    /**
     * Returns filterable attribute values
     *
     * @return void
     */
    public function getElasticProperties()
    {
        $properties = [
            'id'           => $this->product->id,
            'sku'          => $this->product->sku,
            'category_ids' => $this->product->categories->pluck('id')->toArray(),
            'created_at'   => $this->product->created_at,
        ];

        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            $attributeValue = $this->getAttributeValue($attribute);

            if ($attribute->code == 'price') {
                $properties[$attribute->code] = (float) $this->product->getTypeInstance()->getMinimalPrice();
            } elseif ($attribute->type == 'boolean') {
                $properties[$attribute->code] = intval($attributeValue?->{$attribute->column_name});
            } else {
                $properties[$attribute->code] = $attributeValue?->{$attribute->column_name};
            }
        }

        foreach ($this->product->super_attributes as $attribute) {
            foreach ($this->product->variants as $variant) {
                $properties['ca_' . $attribute->code][] = $variant->{$attribute->code};
            }
        }

        return $properties;
    }

    /**
     * Returns attributes to index
     *
     * @return void
     */
    public function getAttributes()
    {
        static $attributes = [];

        if (count($attributes)) {
            return $attributes;
        }

        $attributes = $this->attributeRepository->scopeQuery(function ($query) {
            return $query->where(function ($qb) {
                return $qb->orWhereIn('code', [
                    'name',
                    'new',
                    'featured',
                    'url_key',
                    'short_description',
                    'description',
                ])
                ->orWhere('is_filterable', 1);
            });
        })->get();

        return $attributes;
    }

    /**
     * Returns filterable attribute values
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
                $attributeValues = $attributeValues->where('locale', $this->locale->code)->first();
            }
        }

        return $attributeValues->first();
    }
}