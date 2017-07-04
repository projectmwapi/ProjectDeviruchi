<?php

/*
|--------------------------------------------------------------------------
|  Module Routes
|--------------------------------------------------------------------------
*/



Route::group(['namespace' => 'App\Modules\User\Controllers', 'prefix' => 'api'], function () {

	# Auth controller routes
	Route::group(['prefix' => 'auth'], function() {
		Route::post('login', 'AuthController@logIn');
		Route::post('change-password', 'AuthController@changePassword');
		Route::post('register', 'AuthController@registerUser');
	});

	# User controller routes
	Route::post('user/change-status/{id}', 'UserController@changeUserStatus');
	Route::post('user/batch-change-status', 'UserController@changeUserStatusByBatch');
	Route::post('user/batch-delete', 'UserController@deleteUserByBatch');
	Route::resource('user', 'UserController');

});