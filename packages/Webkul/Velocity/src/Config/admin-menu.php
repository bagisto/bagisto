<?php

return [
    [
        'key' => 'velocity',
        'name' => 'velocity::app.admin.layouts.velocity',
        'route' => 'velocity.admin.content.index',
        'sort' => 5,
        'icon-class' => 'velocity-icon',
    ],  [
        'key' => 'velocity.header',
        'name' => 'velocity::app.admin.layouts.header-content',
        'route' => 'velocity.admin.content.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'velocity.header.content',
        'name' => 'velocity::app.admin.layouts.cms-pages',
        'route' => 'velocity.admin.content.index',
        'sort' => 1,
        'icon-class' => '',
    ], [
        'key' => 'velocity.header.category',
        'name' => 'velocity::app.admin.layouts.category-menu',
        'route' => 'velocity.admin.category.index',
        'sort' => 2,
        'icon-class' => '',
    ]
];