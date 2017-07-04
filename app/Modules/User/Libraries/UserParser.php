<?php

namespace App\Modules\User\Libraries;

use Hash;
use StatusHelper;
use GlobalHelper;
use ResourceHelper;
use \Carbon\Carbon;
use Illuminate\Http\Request;
use \RobThree\Auth\TwoFactorAuth;

use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAccess;
use App\Modules\Utility\Models\ResourceLog;

class UserParser
{

	/**
	 * Get user model resource using the token param
	 * @param  string $token 
	 * @return array
	 */
	public static function getUserByToken($token = '')
	{
		$user = User::whereUserToken($token)->first();
		if ($user) {
			$response = [
				'code'		=> '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'data'		=> $user->toArray()
			];
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Get user model resource using token as param within user_access model
	 * @param  string $token 
	 * @return 
	 */
	public static function getUserFromUserAccessByToken($token = '')
	{
		$user_access = UserAccess::whereAccessToken($token)->first();
		if ($user_access) {
			$response = ResourceHelper::showResource('User', 'User', $user_access->user_id);
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Set new access token and expiry date for the user access using the user id as reference
	 * @param  integer $user_id      
	 * @param  string  $access_token 
	 * @param  string  $date_expiry  
	 * @return array
	 */
	public static function updateUserAccessTokenByUserId($user_id = 0, $access_token = '', $date_expiry = '')
	{
		$user_access = UserAccess::whereUserId($user_id)->first();
		if ($user_access) {
			$user_access->access_token 	= $access_token;
			$user_access->date_expiry 	= $date_expiry;
			if ($user_access->save()) {
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user_access->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}	
		return $response;
	}

	/**
	 * Change user status by user id using the parameter value and update resource log table
	 * @param  Reqeust $request
	 * @param  integer $user_id   
	 * @param  integer $is_active 
	 * @return array
	 */
	public static function updateUserStatusByUserId(Request $request, $user_id = 0, $is_active = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			$user->is_active = $is_active;
			if ($user->save()) {
				$deactivated_at = '';
				// Check if user status is being deactivated
				if ($is_active == 0) {
					$deactivated_at = Carbon::now();
				}
				// Get modifying user
				$user_access = self::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
				// Update resource log
				ResourceHelper::updateResourceLog('User', 'user', $user_id, $user_access['data']['user_id'], 'modified_by', $deactivated_at);
				// Prepare response body
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Update user password into default generic password
	 * @param  integer $user_id  
	 * @param  string  $password 
	 * @return array
	 */
	public static function updateUserPasswordByUserId(Request $request, $user_id = 0, $password = '')
	{
		$user = User::find($user_id);
		if ($user) {
			$user->password = Hash::make($password);
			if ($user->save()) {
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=> '404',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Change user status by batch with user id using the parameter value and update resource log table
	 * @param  Request $request   
	 * @param  array   $user_id   
	 * @param  integer $is_active 
	 * @return json
	 */
	public static function updateUserStatusByBatch(Request $request, $user_id = [], $is_active = 0)
	{
		$user_batch = User::whereIn('user_id', $user_id)->update(['is_active' => $is_active]);
		if ($user_batch) {
			$deactivated_at = null;
			if ($is_active == 0) {
				$deactivated_at = Carbon::now();
			}
			// Get modifying user
			$user_access = self::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
			// Update Resource log modified by
			ResourceLog::whereIn('resource_id', $user_id)->whereResourceModel('User')->whereResourceTable('user')->update(['modified_by' => ($user_access['code'] == '200' ? $user_access['data']['user_id'] : 0), 'deactivated_at' => $deactivated_at]);
			// Retrieve affected user rows
			$updated_user = User::whereIn('user_id', $user_id)->get();
			if (count($updated_user) > 0) {
				$response = [
					'code'		=>  '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'message'	=> $updated_user->toArray()
				];
			}
			else {
				$response = [
					'code'		=>  '500',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=>  '500',
				'status'	=> StatusHelper::getErrorResponseStatus(),
				'message'	=> 'An error occured during this operation'
			];
		}
		return $response;
	}

	/**
	 * Delete user using user_id array as index parameter
	 * @param  Requeust $request 
	 * @param  array    $user_id 
	 * @return array
	 */
	public static function deleteUserByBatch(Request $request, $user_id = [])
	{
		$user_batch = User::whereIn('user_id', $user_id)->delete();
		if ($user_batch) {
			// Get modifying user
			$user_access = self::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
			// Update Resource log modified by
			ResourceLog::whereIn('resource_id', $user_id)->whereResourceModel('User')->whereResourceTable('user')->update(['deleted_by' => ($user_access['code'] == '200' ? $user_access['data']['user_id'] : 0) ]);
			// Prepare response body
			$response = [
				'code'		=>  '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'message'	=> $user_id
			];
		}
		else {
			$response = [
				'code'		=>  '500',
				'status'	=> StatusHelper::getErrorResponseStatus(),
				'message'	=> 'An error occured during this operation'
			];
		}
		return $response;
	}

}