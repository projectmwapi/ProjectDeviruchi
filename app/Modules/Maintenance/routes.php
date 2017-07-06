<?php

/*
|--------------------------------------------------------------------------
|  Module Routes
|--------------------------------------------------------------------------
*/


Route::group(['namespace' => 'App\Modules\Maintenance\Controllers', 'prefix' => 'api/maintenance'], function () {

	# Component controller routes
	Route::resource('/component', 'ComponentController');
        
	# UOM controller routes
	Route::resource('/uom', 'UnitOfMeasurementController');
        
	# Element controller routes
	Route::resource('/element', 'ElementController');
        
        # System group controller routes
        Route::post('/system-group/batch-change-status', 'SystemGroupController@changeSystemGroupStatusByBatch');
        Route::post('/system-group/batch-delete', 'SystemGroupController@deleteSystemGroupByBatch');
	Route::resource('/system-group', 'SystemGroupController');

});