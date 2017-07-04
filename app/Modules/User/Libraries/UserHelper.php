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
use App\Modules\User\Libraries\AuthHelper;

class UserHelper
{

	/**
	 * Prepare user model resource parameters
	 * @param  Request $request 
	 * @param  string $password
	 * @param  integer $is_active
	 * @return array
	 */
	public static function prepareUserParameters(Request $request, $password = '', $is_active = 0)
	{
		$parameters = [
			'role_id'		=> ($request->has('role_id') ? $request->get('role_id') : 0),
			'email'			=> $request->get('email'),
			'password'		=> $password,
			'is_active'		=> $is_active,
			'user_image'	=> ($request->has('user_image') ? $request->get('user_image') : '')
		];	
		return $parameters;
	}

	/**
	 * Prepare user model resource for update
	 * @param  Request $request 
	 * @return array
	 */
	public static function prepareUserUpdateParameters(Request $request)
	{
		$parameters = [
			'role_id'		=> $request->get('role_id'),
			'is_active'		=> $request->get('is_active')
		];
		// Check if user_image is updated; if specified then add as affected row
		if ($request->has('user_image')) {
			if ($request->get('user_image') != '') {
				$parameters['user_image'] = $request->get('user_image');
			}
		}
		return $parameters;
	}	

	/**
	 * Prepare employee model resource parameters
	 * @param  Request $request 
	 * @param  integer $user_id 
	 * @return array           
	 */
	public static function prepareEmployeeParameters(Request $request, $user_id = 0)
	{
		$parameters = [
			'user_id'		=> $user_id,
			'employee_number' => ($request->has('employee_number') ? $request->get('employee_number') : ''),
			'department_id'	=> ($request->has('department_id') ? $request->get('department_id') : 0),
			'first_name'	=> ($request->has('first_name') ? $request->get('first_name') : ''),
			'middle_name'	=> ($request->has('middle_name') ? $request->get('middle_name') : ''),
			'last_name'		=> ($request->has('last_name') ? $request->get('last_name') : ''),
			'remarks'		=> ($request->has('remarks') ? $request->get('remarks') : '')
		];
		return $parameters;
	}

	/**
	 * Prepare employee model resource for update
	 * @param  Request $request 
	 * @return array           
	 */
	public static function prepareEmployeeUpdateParameters(Request $request)
	{
		$parameters = [
			'first_name'	=> $request->get('first_name'),
			'middle_name'	=> ($request->has('middle_name') ? $request->get('middle_name') : ''),
			'last_name'		=> $request->get('last_name'),
			'remarks'		=> $request->get('remarks')
		];
		return $parameters;
	}

	/**
	 * Prepare password history parameters insertion
	 * @param  integer $user_id 
	 * @return array           
	 */
	public static function preparePasswordHistoryParameters($user_id = 0, $password = '')
	{
		$parameters = [
			'password'		=> $password,
			'user_id'		=> $user_id,
			'count'			=> 1
		];
		return $parameters;
	}

	/**
	 * Prepare user access parameters insertion
	 * @param  integer $user_id 
	 * @return array
	 */
	public static function prepareUserAccessParameters($user_id = 0)
	{
		$parameters = [
			'user_id'		=> $user_id
		];
		return $parameters;
	}

}