<?php

use Spatie\ResponseCache\Replacers\CsrfTokenReplacer;
use Spatie\ResponseCache\Serializers\JsonSerializer;
use Webkul\FPC\CacheProfiles\FullPageCacheProfile;
use Webkul\FPC\Hasher\DefaultHasher;
use Webkul\FPC\Replacers\FlashMessagesReplacer;

return [
    /*
     * Determine if the response cache middleware should be enabled.
     */
    'enabled' => env('RESPONSE_CACHE_ENABLED', false),

    'cache' => [
        /*
         * Here you may define the cache store that should be used
         * to store requests. This can be the name of any store
         * that is configured in your app's cache.php config
         */
        'store' => env('RESPONSE_CACHE_DRIVER', 'file'),

        /*
         * The default number of seconds responses will be cached
         * when using the default CacheProfile settings.
         */
        'lifetime_in_seconds' => (int) env('RESPONSE_CACHE_LIFETIME', 60 * 60 * 24 * 7),

        /*
         * If your cache driver supports tags, you may specify a tag
         * name here. All responses will be tagged. When clearing
         * the responsecache only items with that tag flushed.
         *
         * You may use a string or an array here.
         */
        'tag' => env('RESPONSE_CACHE_TAG', ''),
    ],

    'bypass' => [
        /*
         * The header name that will force a bypass of the cache.
         * This is useful when you want to see the performance
         * of your application without the caching enabled.
         */
        'header_name' => env('CACHE_BYPASS_HEADER_NAME'),

        /*
         * The header value that will force a cache bypass.
         */
        'header_value' => env('CACHE_BYPASS_HEADER_VALUE'),
    ],

    'debug' => [
        /*
         * Determines if debug headers are added to cached
         * responses. This can be handy for debugging how
         * response caching is performing in your app.
         */
        'enabled' => env('APP_DEBUG', false),

        /*
         * The name of the http header containing the
         * point at which the response was cached.
         */
        'cache_time_header_name' => env('RESPONSE_CACHE_HEADER_NAME', 'Bagisto-FPC'),

        /*
         * The name of the header for the cache status that
         * indicates whether a response was HIT or MISS.
         */
        'cache_status_header_name' => 'Bagisto-FPC-Status',

        /*
         * The header name for the cache age in seconds.
         */
        'cache_age_header_name' => env('RESPONSE_CACHE_AGE_HEADER_NAME', 'Bagisto-FPC-Age'),

        /*
         * The header name used for the response cache key.
         * This is only added when app.debug is enabled.
         */
        'cache_key_header_name' => 'Bagisto-FPC-Key',
    ],

    /*
     * These query parameters will be ignored when generating
     * the cache key. This is useful for ignoring tracking
     * parameters like UTM tags, gclid and also fbclid.
     */
    'ignored_query_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
    ],

    /*
     * The given class determines if a request should be cached.
     * By default all successful GET-requests will be cached.
     * You can provide your own by using the CacheProfile.
     *
     * Bagisto's profile keeps this behaviour and additionally honours the
     * Full Page Cache settings in Configure -> Cache Management, so the
     * cache can be turned off and its lifetime adjusted without a deploy.
     */
    'cache_profile' => FullPageCacheProfile::class,

    /*
     * This class is responsible for generating a hash for
     * a request. Used for looking up cached responses.
     */
    'hasher' => DefaultHasher::class,

    /*
     * This class is responsible for serializing responses.
     */
    'serializer' => JsonSerializer::class,

    /*
     * Here you may define the replacers that will replace
     * dynamic content from the response. Each replacer
     * must always implement the Replacer interface.
     */
    'replacers' => [
        CsrfTokenReplacer::class,
        FlashMessagesReplacer::class,
    ],
];
