<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Name
    |--------------------------------------------------------------------------
    |
    | Choose a view to display when Breadcrumbs::render() is called.
    | Built in templates are:
    |
    | - 'breadcrumbs::bootstrap5'  - Bootstrap 5
    | - 'breadcrumbs::bootstrap4'  - Bootstrap 4
    | - 'breadcrumbs::bulma'       - Bulma
    | - 'breadcrumbs::foundation6' - Foundation 6
    | - 'breadcrumbs::json-ld'     - JSON-LD Structured Data
    | - 'breadcrumbs::materialize' - Materialize
    | - 'breadcrumbs::tailwind'    - Tailwind CSS
    | - 'breadcrumbs::uikit'       - UIkit
    |
    | Or a custom view, e.g. '_partials/breadcrumbs'.
    |
    */

    'view' => 'breadcrumbs::bootstrap5',

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs File(s)
    |--------------------------------------------------------------------------
    |
    | The file(s) where breadcrumbs are defined. e.g.
    |
    | - base_path('routes/breadcrumbs.php')
    | - glob(base_path('breadcrumbs/*.php'))
    |
    */

    'files' => base_path('routes/breadcrumbs.php'),

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    |
    | Determine when to throw an exception.
    |
    */

    // When route-bound breadcrumbs are used but the current route doesn't have a name (UnnamedRouteException)
    'unnamed-route-exception' => true,

    // When route-bound breadcrumbs are used and the matching breadcrumb doesn't exist (InvalidBreadcrumbException)
    'missing-route-bound-breadcrumb-exception' => true,

    // When a named breadcrumb is used but doesn't exist (InvalidBreadcrumbException)
    'invalid-named-breadcrumb-exception' => true,

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    |
    | Subclass the default classes for more advanced customisations.
    |
    */

    // Manager
    'manager-class' => Diglactic\Breadcrumbs\Manager::class,

    // Generator
    'generator-class' => Diglactic\Breadcrumbs\Generator::class,

];
