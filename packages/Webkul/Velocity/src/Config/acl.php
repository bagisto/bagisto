<?php

return [
    [
        'key'   => 'velocity',
        'name'  => 'velocity::app.admin.layouts.velocity',
        'route' => 'velocity.admin.meta_data',
        'sort'  => 5,
    ],
    [
        'key'   => 'velocity.meta-data',
        'name'  => 'velocity::app.admin.layouts.meta-data',
        'route' => 'velocity.admin.meta_data',
        'sort'  => 5,
    ],
    [
        'key'   => 'velocity.meta-data.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'velocity.admin.store.meta_data',
        'sort'  => 1,
    ],
    [
        'key'   => 'velocity.header',
        'name'  => 'velocity::app.admin.layouts.header-content',
        'route' => 'velocity.admin.content.index',
        'sort'  => 5,
    ],
    [
        'key'   => 'velocity.header.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'velocity.admin.content.create',
        'sort'  => 1,
    ], [
        'key'   => 'velocity.header.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'velocity.admin.content.edit',
        'sort'  => 2,
    ], [
        'key'   => 'velocity.header.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'velocity.admin.content.delete',
        'sort'  => 3,
    ]
];