<?php

namespace App\Modules\User\Libraries;

use Hash;
use StatusHelper;
use GlobalHelper;
use \Carbon\Carbon;
use \RobThree\Auth\TwoFactorAuth;

use App\Modules\User\Models\User;
use App\Modules\User\Models\UserAccess;

class AuthHelper
{

	/**
	 * Authenticate user credentials using email and password
	 * @param  string $email    
	 * @param  string $password 
	 * @return array
	 */
	public static function authenticateCredentials($email = '', $password = '')
	{
		$user = User::whereEmail($email)->first();
		if ($user) {
			// Check if valid password
			if (Hash::check($password, $user->password)) {
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user->toArray()
				];
			}
			else {
				// Update invalid login attempt
				$invalid_attempt = self::incrementInvalidLoginAttemptByUserId($user->user_id);
				// prepare response body
				$response = [
					'code'		=> '422',
					'status'	=> StatusHelper::getNotFoundResponseStatus(),
					'message'	=> [
						'password'	=> ['Invalid password']
					]
				];
			}
		}
		else {
			$response = [
				'code'		=> '422',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> [
					'email'		=> ['Email address not found']
				]
			];
		}
		return $response;
	}

	/**
	 * Increment value of invalid login attempt
	 * @param  integer $user_id 
	 * @return array
	 */
	public static function incrementInvalidLoginAttemptByUserId($user_id = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			$user->invalid_attempt = $user->invalid_attempt + 1;
			if ($user->save()) {
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '404',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=> '500',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Refresh value of invalid attempt by setting it 0
	 * @param  integer $user_id 
	 * @return array
	 */
	public static function refreshInvalidLoginAttemptByUserId($user_id = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			$user->invalid_attempt = 0;
			if ($user->save()) {
				$response = [
					'code'		=> '200',
					'status'	=> StatusHelper::getSuccessResponseStatus(),
					'data'		=> $user->toArray()
				];
			}
			else {
				$response = [
					'code'		=> '404',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'An error occured during this operation'
				];
			}
		}
		else {
			$response = [
				'code'		=> '500',
				'status'	=> StatusHelper::getNotFoundResponseStatus(),
				'message'	=> 'User not found'
			];
		}
		return $response;
	}

	/**
	 * Update user access token value and date expiry using u ser id
	 * @param  integer $user_id      
	 * @param  string  $access_token 
	 * @return array                
	 */
	public static function updateUserAccessTokenByUserId($user_id = 0, $access_token = '', $expiry = '')
	{
		$user_access = UserAccess::whereUserId($user_id)->first();
		if ($user_access) {
			$user_access->access_token = $access_token;
			$user_access->date_expiry = $expiry;
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
				'message'	=> 'Resource not found'
			];
		}
		return $response;
	}

	/**
	 * Set first_login flag into $first_login param of $user_id
	 * @param integer $user_id     
	 * @param integer $first_login 
	 * @return array
	 */
	public static function setUserFirstLoginByUserId($user_id = 0, $first_login = 0)
	{
		$user = User::find($user_id);
		if ($user_id) {
			$user->first_login = $first_login;
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
					'message'	=> 'An error occured for this operation'
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
	 * Set value of locked_at column if invalid attempt has been reached
	 * @param  integer $user_id 
	 * @return array
	 */
	public static function lockUserInvalidAttemptByUserId($user_id = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			$user->locked_at = Carbon::now();
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
					'message'	=> 'An error occured for this operation'
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
	 * Check if locked user was already past from the lock out duration
	 * @param  integer $user_id  
	 * @param  integer $duration 
	 * @return integer : 1 = TRUE 0 = FALSE
	 */
	public static function checkIfPastLockedDuration($user_id = 0, $duration = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			if (Carbon::now() > date('Y-m-d H:i:s', strtotime('+'.$duration.' minutes', strtotime($user->locked_at)))) {
				$response = 1;
			}
			else {
				$response = 0;
			}
		}
		else {
			$response = 0;
		}
		return $response;
	}

	/**
	 * Check if account is already expired by user id
	 * @param  integer $user_id  
	 * @param  integer $duration 
	 * @return integer : 1 = TRUE 0 = FALSE
	 */
	public static function checkIfAccountIsExpired($user_id = 0, $duration = 0)
	{
		$user = User::find($user_id);
		if ($user) {
			if (Carbon::now() > date('Y-m-d H:i:s', strtotime('+'.$duration.' days', strtotime($user->created_at)))) {
				$response = 1;
			}
			else {
				$response = 0;
			}
		}
		else {
			$response = 1;
		}
		return $response;
	}

	/**
	 * Generate access token @RobThree TwoFactorAuth
	 * @return string
	 */
	public static function generateAccessToken()
	{
		$token = new TwoFactorAuth('MegawideAPI');
		return $token->createSecret(160);
	}

}