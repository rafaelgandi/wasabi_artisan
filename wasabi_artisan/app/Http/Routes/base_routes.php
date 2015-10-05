<?php 

// See: http://laravel.com/docs/5.1/routing#route-groups
Route::group(['prefix' => 'wasabi'], function () {	
	Route::get('/get/logs', function () {
		// Logs Viewer Page //
		set_time_limit(0); // For really long logs
		$log_file = App\Files::makeFileIfNotExists(storage_path('debug.logs'));
		echo '<html><head><title>LOGS</title><style>div:hover{background-color:#403B1C;text-decoration:underline;}';
		echo 'span{display:inline-block;font-size:11px;width:35px;color:#DBEAF9;}</style></head>';
		echo '<body style="background-color:#272822;color:#C1EA4F;"><pre style="font-family:consolas;">';
		$ctr = 1;
		foreach (file($log_file) as $line) {
			echo '<div><span>'.$ctr.'</span> '.$line.'</div>';
			$ctr++;
		}
		echo '</pre></body></html>';
	});
	
	// Backup logs to database and clear log file //
	Route::any('/backup/logs', function () {
		if (isset($_POST['xplogs_backup'])) {
			var_dump(App\Xplog::backup());	
			var_dump(App\Xplog::clear());				
		}
		else {
			echo '
				<!DOCTYPE html>
				<html>
				<body>
				<form action="" method="post" >
					Backup wasabi logs:<br>
					<input type="submit" value="Backup" name="xplogs_backup">
					<input type="hidden" name="_token" id="token" value="'.csrf_token().'">
				</form>
				</body>
				</html>';
		}
	});
	
	Route::get('/php/errors', function () {
		error_reporting(E_ALL & ~E_NOTICE); // for development 
		ini_set('display_errors', '1'); // show errors, remove when deployed
		//echo App\Paths::docRoot(get_config('php_error_log'));
		$error_log_file = App\Files::makeFileIfNotExists(storage_path('phperrors.logs'));
		echo '<pre style="font-size: 14px;font-family:consolas;">'.App\Files::get($error_log_file).'</pre>';
		echo 'ok!';
	});
	
	// Set a default redirection route for unsupported browsers //
	Route::get('/browser/nosupport', function () {
		echo 'Sorry your browser version is very old and not supported anymore. Please download the latest verison of this browser to continue.';
	});
	
	// Php Info
	Route::get('/phpinfo', function () {
		phpinfo();
	});
	
	// Server diagnostics 
	Route::get('/server/diagnostics', function () {
		$php_version = floatval(phpversion());	
		echo '<style type="text/css">.warn{color: red;} p{text-align:center;}</style>';
		echo '<br><br><p>PHP version used is '.$php_version.'</p>';
		
		echo '<p>HOSTNAME: '.strtolower(trim(gethostname())).'</p>';
		
		if ($php_version < 5.3) {
			echo '<p class="warn">System cannot work with the current version of php installed</p>';
		}
		
		// Check curl //
		if (function_exists('curl_init')) {
			echo '<p>Curl installed :)</p>';
		}
		else {
			echo '<p class="warn">Oh no, TC requires cURL library to be installed.</p>';
		}
		
		// Check mcrypt //
		if (function_exists('mcrypt_get_key_size')) {
			echo '<p>Mcrypt installed :)</p>';
		}
		else {
			echo '<p class="warn">Oh no, TC requires Mcrypt extension to be installed.</p>';
		}
		
		// Check DateTime //
		if (class_exists('DateTime')) {
			echo '<p>DateTime class is available :)</p>';
		}
		else {
			echo '<p class="warn">Oh no, unable to find DateTime class. This is used for better date management.</p>';
		}
		
		// Check openssl
		if (extension_loaded('openssl')) {
			echo '<p>Open SSL installed :)</p>';
		}
		else {
			echo '<p class="warn">Oh no, TC requires Open SSL extension to be installed.</p>';
		}
		
		// Check xml parser //
		if (function_exists('xml_parser_create') && class_exists('SimpleXMLElement') && function_exists('simplexml_load_string')) {
			echo '<p>XML parser library exists :)</p>';
		}
		else {
			echo '<p class="warn">Can\'t find any XML parser library :(</p>';
		}
		
		// Check soap //
		if (class_exists('SoapClient')) {
			echo '<p>SOAP is enabled :)</p>';
		}
		
		// Check if we can use php sessions //
		if (function_exists('session_start')) {
			echo '<p>PHP Sessions are ready :)</p>';
		}
		else {
			echo '<p class="warn">Oh no, PHP sessions needs to be enabled. The system needs this to function.</p>';
		}
		
		// Check Memcache //
		if (function_exists('memcache_connect')) {
			echo '<p>Memcache is turned on</p>';
		}
		else {
			echo '<p class="warn">Memcache caching is not available. Would have been useful though.</p>';
		}
		
		// Check APC //
		if (extension_loaded('apc') && ini_get('apc.enabled')) {
			echo '<p>APC caching is available</p>';
		}
		else {
			echo '<p class="warn">APC caching is not available. Would have been useful though.</p>';
		}
		
		// Check Fileinfo extension //
		if (function_exists('finfo_open')) {
			echo '<p>Fileinfo extension is installed</p>';
		}
		else {
			echo '<p class="warn">Fileinfo extension is NOT installed.</p>';
		}
	});
});