<?php

return [

    /**
     * Here you can specify the connection to use when building a client.
     */
    'connection' => 'default',

    /**
     * These are the available connections parameters that you can use to connect
     */
    'connections' => [
        'default' => [
            'hosts' => [
                env('ELASTICSEARCH_HOST', 'http://localhost:9200'),
            ],

            'user'   => env('ELASTICSEARCH_USER', null),
            'pass'   => env('ELASTICSEARCH_PASS', null),
        ],

        /**
         * You can connect with API key authentication by setting the `api` key
         * instead of the `user` and `pass` keys.
         */
        'api' => [
            'hosts' => [
                env('ELASTICSEARCH_HOST', null),
            ],

            'key'   => env('ELASTICSEARCH_API_KEY', null),
        ],

        /**
         * You can connect to Elastic Cloud with the Cloud ID using the `cloud` key.
         */
        'cloud' => [
            'id'      => env('ELASTICSEARCH_CLOUD_ID', null),

            /**
             * If you are authenticating with API KEY then set user and pass as null
             */
            'api_key' => env('ELASTICSEARCH_API_KEY', null),

            /**
             * If you are authenticating with username and password then set api_key as null
             */
            'user'    => env('ELASTICSEARCH_USER', null),
            'pass'    => env('ELASTICSEARCH_PASS', null),
        ],
    ],

    /**
     * CA Bundle
     *
     * If you have the http_ca.crt certificate copied during the start of Elasticsearch
     * then the path here
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/connecting.html#auth-http
     */
    'caBundle' => null,

    /**
     * Retries
     *
     * By default, the client will retry n times, where n = number of nodes in
     * your cluster. If you would like to disable retries, or change the number,
     * you can do so here.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/set-retries.html
     */
    'retries' => null,
];
