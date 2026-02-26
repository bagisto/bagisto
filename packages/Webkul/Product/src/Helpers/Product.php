<?php

namespace Webkul\Product\Helpers;

class Product
{
    /**
     * Format the elastic search index name. Elasticsearch index name should be in
     * lowercase. Otherwise elasticsearch will silently fail to create the index.
     */
    public static function formatElasticSearchIndexName(string $channelCode, string $localeCode): string
    {
        return 'products_'.strtolower($channelCode).'_'.strtolower($localeCode).'_index';
    }
}
