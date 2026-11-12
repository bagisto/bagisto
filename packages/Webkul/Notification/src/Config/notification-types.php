<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Types
    |--------------------------------------------------------------------------
    |
    | The notifications a panel can raise, keyed by type. Packages merge their
    | own into `notification_types` from their service provider.
    |
    |   audience    Who is notified, such as `admin`.
    |   group       The `notification_groups` key it is listed under.
    |   class       A Webkul\Notification\Types\AbstractType subclass that renders it.
    |   permission  Permission a reader needs to see it. Optional.
    |   events      Events that raise it, once their transaction commits. Optional.
    |
    */
];
