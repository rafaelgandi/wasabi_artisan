<?php 
use Respect\Validation\Validator as Valid;

Route::get('/carbon', function () { 
	var_dump(Carbon::now());
	echo 'asdf';
	//return 'test'; 
});

Route::get('/paths', function () {
	// See: http://laravel.com/docs/5.1/helpers#paths
	echo 'url() => '.url('/heheh').'<br>';
	echo 'app_path() => '.app_path('asdfasd').'<br>'; 
	echo 'base_path() => '.base_path().'<br>';

	//App\Helpers::load('app_helpers.php');
	echo ENV_PREFIX.'LOCAL_DB_HOST'.'<br>';
	echo env(ENV_PREFIX.'DB_HOST').'<br>';
	echo config('database.connections.mysql.host').'<br>';
	
	var_dump(Valid::int()->notEmpty()->validate('1'));
	//xplog('hello');
});

Route::get('/get/hostname', function () {
	$hostname = strtolower(trim(gethostname()));
	//echo $hostname;
	echo bcrypt('abc123456');
});

Route::get('/sessions', function () {
	_pr(session()->all());
});

Route::get('/throw404', function () {
	// Redirect to 404 page.
	// See: http://laravel.com/docs/5.0/errors#http-exceptions
	abort(404);
});

Route::get('/testing', function () {
	
});
