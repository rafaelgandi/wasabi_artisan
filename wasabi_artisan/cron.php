<?php 
/*
	This is the script called directly by the cron job.
	NOTE: Requires CURL to be enabled.
	
	Sample localhost:
	C:\xampp\php\php.exe C:\xampp\htdocs\wasabi_test\laravel\cron.php

*/
error_reporting(E_ALL & ~E_NOTICE); // for development 
ini_set('display_errors', '1'); // show errors, remove when deployed
set_time_limit(0);
include_once 'environment.php';
$hostname = strtolower(trim(gethostname()));
$echo = '';
$base_url = '';

function curler($_url) {
	// See: http://codular.com/curl-with-php
	$res;
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $_url,
		CURLOPT_FOLLOWLOCATION => true
	));
	$res = curl_exec($curl);
	curl_close($curl);
	return $res;
}

if (defined('ENV_PREFIX')) {
	if (ENV_PREFIX === 'DEV_') { // development
		$base_url = 'http://development.com/wasabi/cron';
	}
	else if (ENV_PREFIX === 'STAGING_') { // staging
		$base_url = 'http://staging.com/wasabi/cron';
	}
	else { // localhost
		$base_url = 'http://localhost/wasabi_test/laravel/public';
	}
	
	// Call scripts here through curl //
	$echo .= curler($base_url.'/cron/attendance/log');
	
	echo 'SERVER HOSTNAME: "'.$hostname.'" BASE_URL: "'.$base_url.'"'."\n".$echo;
}
else { echo 'Unable to find environment.php file.'; }

