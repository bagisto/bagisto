<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Types
    |--------------------------------------------------------------------------
    |
    | What a panel can notify its users about. A type is raised by naming it with
    | `notifier()->notify('order.placed', $order)`, or by listing the events that
    | raise it, in which case the event's first argument is the subject.
    |
    | A notification goes to the type's whole audience, such as every admin, or
    | to the users the type's class returns from `recipients()`, such as the
    | seller an order belongs to.
    |
    | The registry starts empty. A package adds its own types by merging into
    | the `notification_types` key from its service provider, as the Admin
    | package does for the types it ships with.
    |
    | Each entry is keyed by the type and accepts:
    |
    |   audience    Who the type notifies, such as `admin`. The panel serving that audience
    |               lists it, and reads it as the signed in user.
    |   group       The `notification_groups` key the type is listed under.
    |   class       A class extending Webkul\Notification\Types\AbstractType, which builds
    |               the data kept with a notification and the title, message, link and icon
    |               shown for it, in the language of the user reading it.
    |   permission  A permission the reader must hold to see the type, checked by the panel
    |               that serves its audience. Omit for every reader.
    |   events      Events that raise the type. Omit for a type raised only by name.
    |
    */
];
