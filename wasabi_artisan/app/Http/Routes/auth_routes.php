<?php 

Route::any('/login', [
	'as' => 'login',
	'uses' => 'AuthController@login'
]);

Route::get('/logout', [
	'as' => 'logout',
	'uses' => 'AuthController@logout'
]);

Route::any('/signup-confirmation/{uid}', [
	'as' => 'signup_confirmation',
	'uses' => 'AuthController@signUpConfirmation'
]);

Route::any('/resend-signup-confirmation/{uid}', [
	'as' => 'resend_signup_confirmation',
	'uses' => 'AuthController@resendSignUpConfirmation'
]);