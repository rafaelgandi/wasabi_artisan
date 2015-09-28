<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Wasabi //
// Automatically load all the route files inside Routes/ directory //
call_user_func(function () {
	$routes_dir = app_path('Http/Routes');
	$route_files = glob($routes_dir.'/*_routes.php');
	if ($route_files === false) { return; }
	foreach ($route_files as $file) {
		if (is_file($file) && strpos($file, '_routes.php') !== false) {
			include_once $file;
		}
	}
});


