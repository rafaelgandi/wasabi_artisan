<?php 
/* 
	System wide routes
 */

Route::group(['prefix' => 'sys'], function () {
	
	Route::any('/message', [
		'as' => 'sys_message',
		'uses' => 'SysController@message'
	]);
	
});