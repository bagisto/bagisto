<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Groups
    |--------------------------------------------------------------------------
    |
    | The filters a panel lists beside its notification feed. Each notification
    | type names the group it belongs to, and a group holding no type the reader
    | may see is left out for that reader.
    |
    | The registry starts empty. A package adds its own groups by merging into
    | the `notification_groups` key from its service provider, as the Admin
    | package does for the groups it ships with.
    |
    | Each entry is keyed by the group and accepts:
    |
    |   name  Translation key for the label shown in the filter list.
    |   icon  Icon class shown against the label.
    |   sort  Position in the list, lowest first.
    |
    */
];
