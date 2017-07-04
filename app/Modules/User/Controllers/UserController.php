<?php

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Response;
use GlobalHelper;
use StatusHelper;
use ResourceHelper;
use ParameterHelper;

use App\Modules\User\Libraries\UserHelper;
use App\Modules\User\Libraries\UserParser;
use App\Modules\User\Libraries\AuthHelper;

class UserController extends Controller
{

    /**
     * @var Object/Collection
     */
    private $user;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = ParameterHelper::validatePagination($request->all());
        $this->user = ResourceHelper::showAllResource('User', 'User', (isset($pagination['page_size']) ? $pagination['page_size'] : 0), (isset($pagination['page']) ? $pagination['page'] : 0), ['employee']);
        return Response::json($this->user, $this->user['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Generate initial passworrd
        $password = str_random(10);
        echo $password;
        // Prepare parameters for user
        $parameters_user = UserHelper::prepareUserParameters($request, $password);
        // echo " -- TEST 2";exit;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->user = ResourceHelper::showResource('User', 'User', $id, '', ['employee', 'userAccess']);
        return Response::json($this->user, $this->user['code']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->user = ResourceHelper::showResource('User', 'User', $id);
        if ($this->user['code'] == '200') {
            // Prepare update user model resource
            $parameters_user = UserHelper::prepareUserUpdateParameters($request);
            // Update User model resource
            $user = ResourceHelper::updateResource($request, 'User', 'User', $id, $parameters_user, 1, 1);
            // Prepare update employee model resource
            $parameters_employee = UserHelper::prepareEmployeeUpdateParameters($request);
            // Update Employee model resource
            $employee = ResourceHelper::updateResourceByFieldValue($request, 'User', 'Employee', $id, $parameters_employee, 'user_id', 1);
            // If success update of model resource
            if ($user['code'] == '200' && $employee['code'] == '200') {
                $response = $user;
            }
            else {
                $response = [
                    'code'      => '500',
                    'status'    => StatusHelper::getErrorResponseStatus(),
                    'message'   => 'An error occured while updating user'
                ];
            }
        }
        else {
            $response = $this->user;
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->user = ResourceHelper::deleteResource($request, 'User', 'User', $id, 1);
        return Response::json($this->user, $this->user['code']);
    }

    /**
     * Change user status based on parameter value: 0 = DEACTIVATED; 1 = ACTIVE; 2 = FOR APPROVAL; 3 = DSIAPPROVED
     * @param  Request $request   
     * @param  integer $user_id   
     * @param  integer $is_active 
     * @return json             
     */
    public function changeUserStatus(Request $request, $user_id = 0)
    {
        $this->user = UserParser::updateUserStatusByUserId($request, $user_id, $request->get('is_active'));
        if ($this->user['code'] == '200') {
            $password = 'testpass';
            // Send default password
            UserParser::updateUserPasswordByUserId($request, $this->user['data']['user_id'], $password);
        }   
        return Response::json($this->user, $this->user['code']);
    }

    /**
     * Deactive user id by batch as passed by parameter: 0 = DEACTIVATED
     * @param  Request $request 
     * @return json           
     */
    public function changeUserStatusByBatch(Request $request)
    {
        $this->user = UserParser::updateUserStatusByBatch($request, $request->get('user_id'), $request->get('is_active'));
        return Response::json($this->user, $this->user['code']);
    }

    /**
     * Delete user by batch using passed parameter user_id array index
     * @param  Request $request 
     * @return json           
     */
    public function deleteUserByBatch(Request $request)
    {
        $this->user = UserParser::deleteUserByBatch($request, $request->get('user_id'));
        return Response::json($this->user, $this->user['code']);
    }

}
