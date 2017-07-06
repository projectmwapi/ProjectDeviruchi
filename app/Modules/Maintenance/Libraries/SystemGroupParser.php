<?php

namespace App\Modules\Maintenance\Libraries;

use Hash;
use StatusHelper;
use GlobalHelper;
use ResourceHelper;
use \Carbon\Carbon;
use Illuminate\Http\Request;

use App\Modules\Maintenance\Models\SystemGroup;
use App\Modules\Utility\Models\ResourceLog;

use App\Modules\User\Libraries\UserParser;

class SystemGroupParser
{
    
        /**
        * Change user status by batch with user id using the parameter value and update resource log table
        * @param  Request $request   
        * @param  array   $system_group_id   
        * @param  integer $is_active 
        * @return json
        */
        public static function updateSystemGroupStatusByBatch(Request $request, $system_group_id = [], $is_active = 0)
        {
                $system_group_batch = SystemGroup::whereIn('system_group_id', $system_group_id)->update(['is_active' => $is_active]);
                if ($system_group_batch) {
                        $deactivated_at = null;
                        if ($is_active == 0) {
                                $deactivated_at = Carbon::now();
                        }
                        // Get modifying user
                        $user_access = UserParser::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
                        // Update Resource log modified by
                        ResourceLog::whereIn('resource_id', $system_group_id)->whereResourceModel('SystemGroup')->whereResourceTable('user')->update(['modified_by' => ($user_access['code'] == '200' ? $user_access['data']['user_id'] : 0), 'deactivated_at' => $deactivated_at]);
                        // Retrieve affected system group rows
                        $updated_system_group = SystemGroup::whereIn('system_group_id', $system_group_id)->get();
                        if (count($updated_system_group) > 0) {
                                $response = [
                                        'code'		=>  '200',
                                        'status'	=> StatusHelper::getSuccessResponseStatus(),
                                        'message'	=> $updated_system_group->toArray()
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
	 * Delete System Group using system_group_id array as index parameter
	 * @param  Requeust $request 
	 * @param  array    $system_group_id 
	 * @return array
	 */
	public static function deleteSystemGroupByBatch(Request $request, $system_group_id = [])
	{
		$system_group_batch = SystemGroup::whereIn('system_group_id', $system_group_id)->delete();
		if ($system_group_batch) {
			// Get modifying user
			$user_access = UserParser::getUserFromUserAccessByToken($request->header('X-Auth-Token'));
			// Update Resource log modified by
			ResourceLog::whereIn('resource_id', $system_group_id)->whereResourceModel('User')->whereResourceTable('user')->update(['deleted_by' => ($user_access['code'] == '200' ? $user_access['data']['user_id'] : 0) ]);
			// Prepare response body
			$response = [
				'code'		=>  '200',
				'status'	=> StatusHelper::getSuccessResponseStatus(),
				'message'	=> $system_group_id
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