<?php 
// Determine the environment prefix used in the .env file //

call_user_func(function () {
	$hostname = strtolower(trim(gethostname()));
	$server_name = trim($_SERVER['SERVER_NAME']);
	$constant_name = 'ENV_PREFIX';
	if (strpos($server_name, 'sushidigital.ph') !== false) { // Development
		define($constant_name, 'DEV_');
	}
	else if (strpos($server_name, 'staging') !== false) { // Staging
		define($constant_name, 'STAGING_');
	}
	else { // Localhost 
		define($constant_name, 'LOCAL_'); 
	}
});