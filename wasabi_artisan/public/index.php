<?php

/**
 * Laravel - A PHP Framework For Web Artisans (Wasabified)
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

// LM: 08-13-2015
// Determine the environmental variables needed //
require __DIR__.'/../environment.php';
 
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

// LM: 08-17-2015 
// Manually load some needed libraries //
require __DIR__.'/../app/app_autoloader.php'; 

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

// LM: 08-13-2015 [Set custom php error file]
// Make sure to log the php errors in our custom php error logs
// See: http://frumph.net/2010/04/15/how-to-setup-a-php-error-log-for-wordpress/
ini_set('log_errors', 1);
ini_set('error_log', App\Files::makeFileIfNotExists(storage_path('phperrors.logs')));
//ini_set('error_reporting', E_ALL ^ E_NOTICE); // Don't log Notices and Warnings

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);


