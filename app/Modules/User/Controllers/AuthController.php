<?php

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use \Carbon\Carbon;
use Response;
use GlobalHelper;
use ResourceHelper;
use ParameterHelper;

use App\Modules\User\Libraries\UserHelper;
use App\Modules\User\Libraries\UserParser;
use App\Modules\User\Libraries\AuthHelper;
use App\Modules\User\Requests\RegisterUserRequest;

class AuthController extends Controller
{

	/**
	 * @var Object/Collection
	 */
	private $auth;

	/**
	 * Authenticate user credentials and generate auth token
	 * @param  Request $request 
	 * @return json
	 */
	public function logIn(Request $request)
	{	
		$auth_config 	= GlobalHelper::getConfigurationByConfigTypeGroup(1);
		$this->auth 	= AuthHelper::authenticateCredentials($request->get('email'), $request->get('password'));
		if ($this->auth['code'] == '200') {
			// Check if account is expired
			if (AuthHelper::checkIfAccountIsExpired($this->auth['data']['user_id'], $auth_config['PASSWORD_INACTIVE_DAYS_THRESHOLD']) == 1) {
				// Prepare response body
				$response = [
					'code'		=> '409',
					'status'	=> StatusHelper::getErrorResponseStatus(),
					'message'	=> 'You have reached the maximum attempts allowed. Please try again later.'
				];
			}
			else {
				// Check login attempt
				if ($this->auth['data']['invalid_attempt'] >= $auth_config['ACCOUNT_INVALID_LOGIN_THRESHOLD']) {
					// Check if the user login attempt was already past from the account locked out duration
					if (AuthHelper::checkIfPastLockedDuration($this->auth['data']['user_id'], $auth_config['ACCOUNT_LOCKOUT_DURATION_MINS']) == 0) {
						// Generate access token
						$access_token = AuthHelper::generateAccessToken();
						// Set expiry of access token
						$access_expiry = date('Y-m-d H:i:s', strtotime('+'.$auth_config['ACCOUNT_AUTO_LOG_OUT_MINS'].' minutes'));
						// Update access token by user id
						$user_access = AuthHelper::updateUserAccessTokenByUserId($this->auth['data']['user_id'], $access_token, $access_expiry);
						// Refresh invalid attemps
						AuthHelper::refreshInvalidLoginAttemptByUserId($this->auth['data']['user_id']);
						// Get user details
						$user = ResourceHelper::showResource('User', 'User', $this->auth['data']['user_id'], '', ['employee', 'userAccess']);
						// Prepare response body
						$response = $user;
					}
					else {
						// Check if the user was already locked
						if ($this->auth['data']['locked_at'] == '' || is_null($this->data['data']['locked_at']) == TRUE) {
							// Set locked at date for user reached invalid date
							AuthHelper::lockUserInvalidAttemptByUserId($this->auth['data']['user_id']);
						}
						// Prepare response body
						$response = [
							'code'		=> '409',
							'status'	=> StatusHelper::getErrorResponseStatus(),
							'message'	=> 'You have reached the maximum attempts allowed. Please try again later.'
						];
					}
				}
				else {
					// Generate access token
					$access_token = AuthHelper::generateAccessToken();
					echo $access_token;
					// Set expiry of access token
					$access_expiry = date('Y-m-d H:i:s', strtotime('+'.$auth_config['ACCOUNT_AUTO_LOG_OUT_MINS'].' minutes'));
					// Update access token by user id
					$user_access = AuthHelper::updateUserAccessTokenByUserId($this->auth['data']['user_id'], $access_token, $access_expiry);
					// Refresh invalid attemps
					AuthHelper::refreshInvalidLoginAttemptByUserId($this->auth['data']['user_id']);
					// Get user details
					$user = ResourceHelper::showResource('User', 'User', $this->auth['data']['user_id'], '', ['employee', 'userAccess']);
					// Prepare response body
					$response = $user;
				}
			}
		}
		else {
			$response = $this->auth;
		}
		return Response::json($response, $response['code']);
	}

	/**
	 * Change of password of user using access token as parameter or header
	 * @param  Request $request 
	 * @return json
	 */
	public function changePassword(Request $request)
	{
		$auth_config 	= GlobalHelper::getConfigurationByConfigTypeGroup(1);
		// Check if API was called via forgot or first time login 
		if ($request->get('is_forgot') == 1) {
			// Get user model resource by using user_token as resource field
			$user = UserParser::getUserByToken($request->header('X-Auth-Token'));
		}
		// Get user model resource by using access_token in user_access 
		$user = UserParser::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
		if ($user['code'] == '200') {
			// Check if first time loggin in
			if ($user['data']['first_login'] == 1) {
				// Set first login flag into 0
				AuthHelper::setUserFirstLoginByUserId($user['data']['user_id'], 0);
			}
			// Set expiry of access token
			$access_expiry = date('Y-m-d H:i:s', strtotime('+'.$auth_config['ACCOUNT_AUTO_LOG_OUT_MINS'].' minutes'));
			// Generate access token
			$access_token = AuthHelper::generateAccessToken();
			// Update user_access token
			$user_access = UserParser::updateUserAccessTokenByUserId($user['data']['user_id'], $access_token, $access_expiry);
			// Prepare parameters for password history
			$parameters_history = UserHelper::preparePasswordHistoryParameters($user['data']['user_id'], $request->get('confirm_password'));
            // Insert password history model
            $password_history = ResourceHelper::storeResource($request, 'User', 'PasswordHistory', $parameters_history, 1);
            // Prepare update password parameters
            $updated_password = [
            	'password'		=> $request->get('confirm_password'),
            	'user_token'	=> ''
            ];
            // Update password
            ResourceHelper::updateResource($request, 'User', 'User', $user['data']['user_id'], ['password' => $request->get('confirm_password')], 1);
			// Retrieve user details
			$user = ResourceHelper::showResource('User', 'User', $user['data']['user_id'], '', ['employee', 'userAccess']);
			// Prepare response body
			$response = $user;
		}
		else {
			$response = $user;
		}
		return Response::json($response, $response['code']);
	}

	/**
	 * Register user that will be saved on user, employee, user_access and password history
	 * @param  Request          $request    
	 * @param  ValidationHelper $validation 
	 * @return json
	 */
	public function registerUser(RegisterUserRequest $request)
	{
        // Generate initial passworrd
        $password = str_random(10);
        echo $password;
		// Prepare user parameters
		$parameters_user = UserHelper::prepareUserParameters($request, $password, 2);
        // Insert user model
        $user = ResourceHelper::storeResource($request, 'User', 'User', $parameters_user, 1, 1);
        // If successful insert of model resource
        if ($user['code'] == '201') {
        	// Prepare parameters for Employee
            $parameters_employee = UserHelper::prepareEmployeeParameters($request, $user['data']['user_id']);
            // Insert employee model
            $employee = ResourceHelper::storeResource($request, 'User', 'Employee', $parameters_employee, 1);
            // Prepare parameters for password history
            $parameters_history = UserHelper::preparePasswordHistoryParameters($user['data']['user_id'], $password);
            // Insert password history model
            $password_history = ResourceHelper::storeResource($request, 'User', 'PasswordHistory', $parameters_history, 1);
            // Prepare parameters for user access
            $parameters_user_access = UserHelper::prepareUserAccessParameters($user['data']['user_id']);
            // Insert user access model
            $user_access = ResourceHelper::storeResource($request, 'User', 'UserAccess', $parameters_user_access, 1);
            // If success insert of model resource
            if ($employee['code'] == '201' && $password_history['code'] == '201') {
                $response = ResourceHelper::showResource('User', 'User', $user['data']['user_id']);
            }
            else {
                // Delete and rollback
                ResourceHelper::forceDeleteResource('User', 'User', $user['data']['user_id'], 'user_id');
                ResourceHelper::forceDeleteResource('User', 'Employee', $user['data']['user_id'], 'user_id');
                ResourceHelper::forceDeleteResource('User', 'UserAccess', $user['data']['user_id'], 'user_id');
                ResourceHelper::forceDeleteResource('User', 'PasswordHistory', $user['data']['user_id'], 'user_id');
                // Prepare response body
                $response = [
                    'code'      => '500',
                    'status'    => StatusHelper::getErrorResponseStatus(),
                    'message'   => 'An error occured while saving new user'
                ];
            }
    	}
        else {
            $response = [
                'code'      => '500',
                'status'    => StatusHelper::getErrorResponseStatus(),
                'message'   => 'An error occured while saving new user'
            ];
        }
        return Response::json($response, $response['code']);
	}

}
