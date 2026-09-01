<?php

use League\Fractal\Serializer\DataArraySerializer;

/*
|--------------------------------------------------------------------------
| Prettus Repository Config
|--------------------------------------------------------------------------
|
| Settings for the l5-repository package every Bagisto repository is built
| on. Bagisto extends it in `Webkul\Core\Eloquent\Repository`, so the cache
| options below are read through that class rather than used directly.
|
*/
return [

    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    |
    | How many records a repository returns per page when a request does not
    | ask for a limit of its own.
    |
    */
    'pagination' => [
        'limit' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Fractal Presenter Config
    |--------------------------------------------------------------------------
    |
    | The serializer presenters transform their output with, and the request
    | parameter that names the relations to include.
    |
    | Available serializers: ArraySerializer, DataArraySerializer,
    | JsonApiSerializer.
    |
    */
    'fractal' => [
        'params' => [
            'include' => 'include',
        ],
        'serializer' => DataArraySerializer::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Config
    |--------------------------------------------------------------------------
    |
    | Repository level caching of read queries, and the invalidation that keeps
    | it honest. Reads are cached per query signature and forgotten whenever the
    | repository writes.
    |
    */
    'cache' => [
        /*
        |--------------------------------------------------------------------------
        | Cache Status
        |--------------------------------------------------------------------------
        |
        | Whether repositories cache their reads. This is the default for every
        | repository; the `repositories` list below turns it on for the few that
        | are read constantly and written rarely.
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Cache Minutes
        |--------------------------------------------------------------------------
        |
        | How long a cached read is kept before it expires on its own, in minutes.
        |
        */
        'minutes' => 10080,

        /*
        |--------------------------------------------------------------------------
        | Cache Repository
        |--------------------------------------------------------------------------
        |
        | The container binding the cache is resolved from, which must give back
        | an `Illuminate\Contracts\Cache\Repository`. The default follows whatever
        | store `CACHE_STORE` names.
        |
        */
        'repository' => 'cache',

        /*
        |--------------------------------------------------------------------------
        | Cache Clean Listener
        |--------------------------------------------------------------------------
        |
        | What happens to a repository's cached reads once it writes. Bagisto
        | handles this in `Webkul\Core\Listeners\CleanCacheRepository`, which
        | forgets the repository's keys and then stops tracking them.
        |
        */
        'clean' => [

            /*
            |--------------------------------------------------------------------------
            | Enable Clear Cache On Repository Changes
            |--------------------------------------------------------------------------
            |
            | Whether a write invalidates what the repository has cached. Turning
            | this off while caching is on serves stale reads until they expire.
            |
            */
            'enabled' => true,

            /*
            |--------------------------------------------------------------------------
            | Actions In Repository
            |--------------------------------------------------------------------------
            |
            | Which writes invalidate the cache. Each fires only when the write goes
            | through the repository, so a model saved directly leaves the cache as
            | it was.
            |
            */
            'on' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],

        'params' => [
            /*
            |--------------------------------------------------------------------------
            | Skip Cache Params
            |--------------------------------------------------------------------------
            |
            | The query parameter that makes a request read past the cache.
            |
            | Ex: http://prettus.local/?search=lorem&skipCache=true
            |
            */
            'skipCache' => 'skipCache',
        ],

        /*
        |--------------------------------------------------------------------------
        | Methods Allowed
        |--------------------------------------------------------------------------
        |
        | Which read methods are cacheable, as an allow list or a deny list. Leaving
        | both null caches every one of them.
        |
        | Cacheable methods: all, paginate, find, findByField, findWhere,
        | getByCriteria.
        |
        | Ex:
        |
        | 'only'   => ['all', 'paginate'],
        | 'except' => ['find'],
        |
        */
        'allowed' => [
            'only' => null,
            'except' => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | Cached Repositories
        |--------------------------------------------------------------------------
        |
        | The repositories that cache their reads, overriding the disabled default
        | above. These answer the same few questions on nearly every request and
        | change only when an admin saves a setting.
        |
        | Each entry may also override `minutes`, `clean` and `allowed` for that
        | repository alone, in the same shape as the settings above.
        |
        */
        'repositories' => [
            'Webkul\Core\Repositories\CoreConfigRepository' => [
                'enabled' => true,
            ],

            'Webkul\Core\Repositories\ChannelRepository' => [
                'enabled' => true,
            ],

            'Webkul\Core\Repositories\CountryRepository' => [
                'enabled' => true,
            ],

            'Webkul\Core\Repositories\CountryStateRepository' => [
                'enabled' => true,
            ],

            'Webkul\Core\Repositories\CurrencyRepository' => [
                'enabled' => true,
            ],

            'Webkul\Core\Repositories\LocaleRepository' => [
                'enabled' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Criteria Config
    |--------------------------------------------------------------------------
    |
    | Settings of request parameters names that will be used by Criteria
    |
    */
    'criteria' => [
        /*
        |--------------------------------------------------------------------------
        | Accepted Conditions
        |--------------------------------------------------------------------------
        |
        | Conditions accepted in consultations where the Criteria
        |
        | Ex:
        |
        | 'acceptedConditions'=>['=','like']
        |
        | $query->where('foo','=','bar')
        | $query->where('foo','like','bar')
        |
        */
        'acceptedConditions' => [
            '=',
            'like',
            'in',
        ],

        /*
        |--------------------------------------------------------------------------
        | Request Params
        |--------------------------------------------------------------------------
        |
        | Request parameters that will be used to filter the query in the repository
        |
        | Params :
        |
        | - search : Searched value
        |   Ex: http://prettus.local/?search=lorem
        |
        | - searchFields : Fields in which research should be carried out
        |   Ex:
        |    http://prettus.local/?search=lorem&searchFields=name;email
        |    http://prettus.local/?search=lorem&searchFields=name:like;email
        |    http://prettus.local/?search=lorem&searchFields=name:like
        |
        | - filter : Fields that must be returned to the response object
        |   Ex:
        |   http://prettus.local/?search=lorem&filter=id,name
        |
        | - orderBy : Order By
        |   Ex:
        |   http://prettus.local/?search=lorem&orderBy=id
        |
        | - sortedBy : Sort
        |   Ex:
        |   http://prettus.local/?search=lorem&orderBy=id&sortedBy=asc
        |   http://prettus.local/?search=lorem&orderBy=id&sortedBy=desc
        |
        | - searchJoin: Specifies the search method (AND / OR), by default the
        |               application searches each parameter with OR
        |   EX:
        |   http://prettus.local/?search=lorem&searchJoin=and
        |   http://prettus.local/?search=lorem&searchJoin=or
        |
        */
        'params' => [
            'search' => 'search',
            'searchFields' => 'searchFields',
            'filter' => 'filter',
            'orderBy' => 'orderBy',
            'sortedBy' => 'sortedBy',
            'with' => 'with',
            'searchJoin' => 'searchJoin',
            'withCount' => 'withCount',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Generator Config
    |--------------------------------------------------------------------------
    |
    | Where `make:repository` and its siblings write the classes they scaffold.
    | Bagisto packages are created by hand, so these are the package defaults.
    |
    */
    'generator' => [
        'basePath' => app()->path(),
        'rootNamespace' => 'App\\',
        'stubsOverridePath' => app()->path(),
        'paths' => [
            'models' => 'Entities',
            'repositories' => 'Repositories',
            'interfaces' => 'Repositories',
            'transformers' => 'Transformers',
            'presenters' => 'Presenters',
            'validators' => 'Validators',
            'controllers' => 'Http/Controllers',
            'provider' => 'RepositoryServiceProvider',
            'criteria' => 'Criteria',
        ],
    ],
];
