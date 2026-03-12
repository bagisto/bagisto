<?php

use Shetabit\Visitor\Drivers\JenssegersAgent;
use Shetabit\Visitor\Drivers\UAParser;
use Shetabit\Visitor\Resolvers\GeoIp\NullResolver;
use Shetabit\Visitor\Resolvers\GeoIp\SteveBaumanResolver;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following driver to use.
    | You can switch to a different driver at runtime.
    |
    */
    'default' => 'jenssegers',

    // except save request or route names
    'except' => ['login', 'register'],

    // name of the table which visit records should save in
    'table_name' => 'visits',

    /*
    |--------------------------------------------------------------------------
    | List of Drivers
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Drivers above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use for
    | here with the same name. You will have to implement
    | Shetabit\Visitor\Contracts\UserAgentParser in your driver.
    |
    */
    'drivers' => [
        'jenssegers' => JenssegersAgent::class,
        'UAParser' => UAParser::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | GeoIP Enrichment
    |--------------------------------------------------------------------------
    |
    | When enabled, visits can be enriched with geolocation data stored
    | in the "geo_raw" JSON column. This package ships with a default
    | resolver (stevebauman/location). You may implement your own by
    | adding it to the drivers array below. Each driver must implement
    | Shetabit\Visitor\Contracts\GeoIpResolver.
    |
    */
    'geoip' => false,           // disable enrichment by default
    'resolver' => 'stevebauman',   // default resolver
    'resolvers' => [
        'stevebauman' => SteveBaumanResolver::class,
        'null' => NullResolver::class,
    ],
];
