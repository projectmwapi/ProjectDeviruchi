<?php

namespace App\Libraries;

use Hash;
use Schema;
use \Carbon\Carbon;
use App\Libraries\StatusHelper;
use App\Modules\User\Libraries\UserHelper;
use App\Modules\User\Libraries\UserParser;

use Illuminate\Http\Request;

class ResourceHelper
{

	/**
	 * Validate if pagination variables are correct
	 * @param  integer $start 
	 * @param  integer $limit 
	 * @return boolean
	 */
	private static function validatePagination($page_size = 0, $page = 0)
	{
		if ($page_size > 0 && $page > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Get all resource in the model within the module; if start and limit is specified then add in query; use relationship
	 * @param  string  $module       
	 * @param  string  $model_name   
	 * @param  integer $start        
	 * @param  integer $limit      
	 * @param  array   $relationship   
	 * @return array                
	 */
	public static function showAllResource($module = '', $model_name = '', $page_size = 0, $page = 0, $relationship = [])
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = $source::with($relationship)->get();
		$pagination = self::validatePagination($page_size, $page);
		if ($pagination == true) {
			// $model = $source::skip($start)->take($limit)->get();
			$model = $source::paginate($page);
			if (count($relationship)) {
				// $model = $source::with($relationship)->take($limit)->get();
				$model = $source::with($relationship)->paginate(1);
			}
		}
		if (count($model) > 0) {
			$response = [
				'code'		=> '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $model->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Get soft deleted resource in the model within the module using the resource id as indetifier; use relationship
	 * @param  string  $module       
	 * @param  string  $model_name   
	 * @param  integer $resource_id  
	 * @param  string  $resource_field
	 * @param  array   $relationship 
	 * @return array                
	 */
	public static function showAllDeletedResource($module = '', $model_name = '', $start = 0, $limit = 0, $relationship = [])
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = $source::onlyTrashed()->get();
		$pagination = self::validatePagination($start, $limit);
		if ($pagination == true) {
			$model = $source::onlyTrashed()->skip($start)->take($limit)->get();
			if (count($relationship)) {
				$model = $source::onlyTrashed()->with($relationship)->take($limit)->get();
			}
		}
		if (count($model) > 0) {
			$response = [
				'code'		=> '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $model->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Get resource in the model within the module using the resource id as indetifier; use relationship
	 * @param  string  $module       
	 * @param  string  $model_name   
	 * @param  integer $resource_id  
	 * @param  string  $resource_field
	 * @param  array   $relationship 
	 * @return array                
	 */
	public static function showResource($module ='', $model_name ='', $resource_id = 0, $resource_field = '', $relationship = [])
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = $source::find($resource_id);
		if (count($relationship)) {
			// $model = $source::with($relationship)->where($resource_field, '=', $resource_id)->first();
			$model = $source::with($relationship)->find($resource_id);
		}
		if ($model) {
			$response = [
				'code'		=> '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $model->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Get soft deleted resource in the model within the module using the resource id as indetifier; use relationship
	 * @param  string  $module       
	 * @param  string  $model_name   
	 * @param  integer $resource_id  
	 * @param  string  $resource_field
	 * @param  array   $relationship 
	 * @return array                
	 */
	public static function showDeletedResource($module ='', $model_name ='', $resource_id = 0, $resource_field = '', $relationship = [])
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = $source::onlyTrashed()->where($resource_field, $resource_id)->first();
		if (count($relationship)) {
			$model = $source::onlyTrashed()->with($relationship)->where($resource_field, '=', $resource_id)->first();
		}
		if ($model) {
			$response = [
				'code'		=> '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $model->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Store resource in the model within the module using the request parameters as fields and values
	 * @param  Reqeust $request_instance
	 * @param  string $module
	 * @param  string $model_name
	 * @param  array  $request 
	 * @param  integer $full_response
	 * @return array
	 */
	public static function storeResource(Request $request_instance, $module = '', $model_name = '', $request = [], $full_response = 0, $is_log = 0, $created_by = 0)
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = new $source;
		$resource_id = $model->getKeyName();
		foreach ($request as $field => $value) {
			if (Schema::hasColumn($model->getTable(), $field)) {
				$model->{$field} = ($field == 'password' ? Hash::make($value) : $value);
				// $model->{$field} = ($field == 'password' ? bcrypt($value) : $value);
			}
		}
		if ($model->save()) {
			if ($is_log > 0) {
				$user_access = UserParser::getUserFromUserAccessByToken($request_instance->header('X-Auth-Token'));
				// Save resource log
				self::storeResourceLog($model_name, $model->getTable(), $model->$resource_id, ($user_access['code'] == '200' ? $user_access['data']['user_id'] : $model->$resource_id) );
			}
			// Prepare response body
			$response['code'] 	= '201';
			$response['status']	= StatusHelper::getSuccessResponseStatus();
			if ($full_response > 0) {
				$response['data'] = $model->toArray();
			}
			else {
				$response['id']	= $model->$resource_id;
			}
		}
		else {
			$response = [
				'code'		=> '500',
				'status'	=> StatusHelper::getErrorResponseStatus(),
				'message'	=> 'An error occured while storing new resource.'
			];
		}
		return $response;
	}

	/**
	 * Store resource log mapped into model
	 * @param  string  $model_name      
	 * @param  string  $table_name  
	 * @param  integer $resource_id 
	 * @return array               
	 */
	public static function storeResourceLog($model_name = '', $table_name = '', $resource_id = 0, $created_by = 0)
	{
		$source = "\App\Modules\Utility\Models\ResourceLog";
		$model = new $source;
		// Set field values
		$model->resource_model = $model_name;
		$model->resource_table = $table_name;
		$model->resource_id = $resource_id;
		$model->created_by = $created_by;
		// Save new resource log
		if ($model->save()) {
			$response = [
				'code'		=> '201',
				'status' 	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $model->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '500',
				'status' 	=> StatusHelper::getErrorResponseStatus(),
				'message'	=> 'An error occured while storing log'
			];
		}
		return $response;
	}

	/**
	 * Store mutltiple resource in the model within the module using the request parameters as fields and values
	 * @param  string  $module        
	 * @param  string  $model_name    
	 * @param  array   $request       
	 * @param  integer $full_response 
	 * @return array                 
	 */
	public static function storeMultipleResource(Request $request_instance, $module = '', $model_name = '', $request = [], $full_response = 0)
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		foreach ($request as $resource) {
			$model = new $source;
			$resource_id = $model->getKeyName();
			foreach ($resource as $field => $value) {
				$model->{$field} = ($field == 'password' ? Hash::make($value) : $value);
				// $model->{$field} = ($field == 'password' ? bcrypt($value) : $value);
			}
			if ($model->save()) {
				if ($full_response > 0) {
					$data[] = $model->toArray();
				}
				else {
					$data[]	= $model->$resource_id;
				}
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured while storing multiple resource.'
				];
				break;
			}
		}
		if (count($data) > 0) {
			$response['code'] 	= '201';
			$response['status']	= StatusHelper::getSuccessResponseStatus();
			$response['data'] 	= $data;
		}
		return $response;
	}

	/**
	 * Update resource in the model within the module using the resource id as identifier as request parameters as fields and values 
	 * @param  string  $module      
	 * @param  string  $model       
	 * @param  integer $resource_id 
	 * @param  array   $request     
	 * @param  integer $full_response
	 * @return array
	 */
	public static function updateResource(Request $request_instance, $module = '', $model_name = '', $resource_id = 0, $request = [], $full_response = 0, $is_log = 0)
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		if ($model = $source::find($resource_id)) {
			$deactivated_at = '';
			$reference_key = $model->getKeyName();
			foreach ($request as $field => $value) {
				if (Schema::hasColumn($model->getTable(), $field)) {
					$model->{$field} = ($field == 'password' ? Hash::make($value) : $value);
					// Check if resource status is being deactiveated
					if ($field == 'is_active') {
						if ($value == 0) {
							$deactivated_at = Carbon::now();
						}
					}
					// $model->{$field} = ($field == 'password' ? bcrypt($value) : $value);
				}
				// $model->{$field} = ($field == 'password' ? bcrypt($value) : $value);
			}
			if ($model->save()) {
				if ($is_log	> 0) {
					$user_access = UserParser::getUserFromUserAccessByToken($request_instance->header('X-Auth-Token'));
					// Update resource log mapped into model
					self::updateResourceLog($model_name, $model->getTable(), $model->$reference_key, $user_access['data']['user_id'], 'modified_by', $deactivated_at);
				}
				// Prepare response body
				$response['code'] 	= '200';
				$response['status']	= StatusHelper::getSuccessResponseStatus();
				if ($full_response > 0) {
					$response['data'] = $model->toArray();
				}
				else {
					$response['id']	= $model->$reference_key;
				}
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured while updating resource.'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Update resource in the model using the resource id and resource field as identifier using the request parameters as values
	 * @param  string  $module         
	 * @param  string  $model          
	 * @param  integer $resource_id    
	 * @param  array   $request        
	 * @param  string  $resource_field 
	 * @param  integer $full_response  
	 * @return array                  
	 */
	public static function updateResourceByFieldValue(Request $request_instance, $module = '', $model_name = '', $resource_id = 0, $request = [], $resource_field = '', $full_response = 0)
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		$model = $source::where($resource_field, $resource_id)->first();
		if ($model) {
			$reference_key = $model->getKeyName();
			foreach ($request as $field => $value) {
				if (Schema::hasColumn($model->getTable(), $field)) {
					$model->{$field} = ($field == 'password' ? Hash::make($value) : $value);
				}
			}
			if ($model->save()) {
				// Prepare response body
				$response['code'] 	= '200';
				$response['status']	= StatusHelper::getSuccessResponseStatus();
				if ($full_response > 0) {
					$response['data'] = $model->toArray();
				}
				else {
					$response['id']	= $model->$reference_key;
				}
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured while updating resource.'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message' 	=> 'Resource not found'
			];
		}
		return $response;
	}

	/**
	 * Update resource log mapped into model
	 * @param  string  $model_name     
	 * @param  string  $table_name     
	 * @param  integer $resource_id    
	 * @param  integer $modified_by    
	 * @param  string  $deactivated_at 
	 * @return array                  
	 */
	public static function updateResourceLog($model_name = '', $table_name = '', $resource_id = 0, $modified_by = 0, $reference_field = '', $deactivated_at = '')
	{
		$source = "\App\Modules\Utility\Models\ResourceLog";
		$model = $source::whereResourceModel($model_name)->whereResourceTable($table_name)->whereResourceId($resource_id)->first();
		// Check if resource log is found
		if ($model) {
			// Set field values
			$model->{$reference_field} = $modified_by;
			$model->deactivated_at = ($deactivated_at != '' ? $deactivated_at : null);
			// Save new resource log
			if ($model->save()) {
				$response = [
					'code'		=> '201',
					'status' 	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $model->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '500',
					'status' 	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured while storing log'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource log not found'
			];
		}
		return $response;
	}

	/**
	 * Soft delete resource in the model within the module using the resource id as identifier
	 * @param  string  $module      
	 * @param  string  $model_name  
	 * @param  integer $resource_id 
	 * @return array               
	 */
	public static function deleteResource(Request $request_instance, $module = '', $model_name = '', $resource_id = 0, $is_log = 0)
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		if ($model = $source::find($resource_id)) {
			$reference_key = $model->getKeyName();
			if ($model->delete()) {
				if ($is_log	> 0) {
					$user_access = UserParser::getUserFromUserAccessByToken($request_instance->header('X-Auth-Token'));
					// Update resource log mapped into model
					self::updateResourceLog($model_name, $model->getTable(), $model->$reference_key, $user_access['data']['user_id'], 'deleted_by');
				}
                $response = [
                    'code'      => '200',
                    'status'    => StatusHelper::getSuccessResponseStatus(),
                    'id'        => $model->toArray()
                ];
            }
            else {
                $response = [
                    'code'      => '500',
                    'status'    => StatusHelper::getErrorResponseStatus(),
                    'message'	=> 'An error occured while deleting resource.'
                ];
            }
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Restore soft deleted resource in the model within the module using the resource id as identifier
	 * @param  string  $module      
	 * @param  string  $model_name  
	 * @param  integer $resource_id 
	 * @return array              
	 */
	public static function restoreResource($module = '', $model_name = '', $resource_id = 0, $resource_field = '')
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		if ($model = $source::onlyTrashed()->where($resource_field, $resource_id)) {
			if ($model->restore()) {
                $response = [
                    'code'      => '200',
                    'status'    => StatusHelper::getSuccessResponseStatus(),
                    'id'        => $resource_id
                ];
            }
            else {
                $response = [
                    'code'      => '500',
                    'status'    => StatusHelper::getErrorResponseStatus(),
                    'message'	=> 'An error occured while deleting resource.'
                ];
            }
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

	/**
	 * Force delete resource in the model within the module using the resource id as identifier
	 * @param  string  $module      
	 * @param  string  $model_name  
	 * @param  integer $resource_id 
	 * @return array
	 */
	public static function forceDeleteResource($module = '', $model_name = '', $resource_id = 0, $resource_field = '')
	{
		$source = "\App\Modules\\".$module."\Models\\".$model_name;
		if ($model = $source::withTrashed()->where($resource_field, $resource_id)) {
			if ($model->forceDelete()) {
                $response = [
                    'code'      => '200',
                    'status'    => StatusHelper::getSuccessResponseStatus(),
                    'id'        => $resource_id
                ];
            }
            else {
                $response = [
                    'code'      => '500',
                    'status'    => StatusHelper::getErrorResponseStatus(),
                    'message'	=> 'An error occured while deleting resource.'
                ];
            }
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'Resource not found.'
			];
		}
		return $response;
	}

}