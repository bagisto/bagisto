<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;

/**
 * Auth routes.
 */
require 'web/auth-routes.php';

Route::group(['middleware' => ['admin', NoCacheMiddleware::class], 'prefix' => config('app.admin_url')], function () {
    /**
     * Sales routes.
     */
    require 'web/sales-routes.php';

    /**
     * Catalog routes.
     */
    require 'web/catalog-routes.php';

    /**
     * Customers routes.
     */
    require 'web/customers-routes.php';

    /**
     * Marketing routes.
     */
    require 'web/marketing-routes.php';

    /**
     * CMS routes.
     */
    require 'web/cms-routes.php';

    /**
     * Reporting routes.
     */
    require 'web/reporting-routes.php';

    /**
     * Appearance routes.
     */
    require 'web/appearance-routes.php';

    /**
     * Settings routes.
     */
    require 'web/settings-routes.php';

    /**
     * Configuration routes.
     */
    require 'web/configuration-routes.php';

    /**
     * Notification routes.
     */
    require 'web/notification-routes.php';

    /**
     * Help & Resources routes.
     */
    require 'web/help-routes.php';

    /**
     * Remaining routes.
     */
    require 'web/rest-routes.php';
});
