<?php

/*
|--------------------------------------------------------------------------
| Checking For Bagisto's Installation
|--------------------------------------------------------------------------
|
| Checking for Bagisto's installation. If installation is done then move to
| Laravel portion.
|
*/

$location = str_replace('\\', '/', getcwd());
$currentLocation = explode("/", $location);
$desiredLocation = implode("/", $currentLocation);
$installFile = $desiredLocation . '/installer' . '/install.php';

if (file_exists($installFile)) {
    $install = require __DIR__.'/installer/install.php';
} else {
    $install = null;
}

if (! is_null($install)) {

    /*
    |--------------------------------------------------------------------------
    | Redirect To Installer Page
    |--------------------------------------------------------------------------
    |
    | If somehow anything went wrong then this will redirect to the installer
    | page.
    |
    */

    header("Location: $install");

} else {

    /*
    |--------------------------------------------------------------------------
    | Register The Auto Loader
    |--------------------------------------------------------------------------
    |
    | Composer provides a convenient, automatically generated class loader for
    | this application. We just need to utilize it! We'll simply require it
    | into the script here so we don't need to manually load our classes.
    |
    */

    require __DIR__.'/../vendor/autoload.php';
}

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);