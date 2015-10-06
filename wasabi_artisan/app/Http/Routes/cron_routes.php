<?php 
/* 
	Cron sepcific routes only
 */
Route::group(['prefix' => 'cron'], function () {
	
	// Check if cron ran //
	Route::get('/attendance/log', function () {
		xplog('Cron ran!');
		return 'Cron ran!';
	});
	
});