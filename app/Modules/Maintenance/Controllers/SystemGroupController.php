<?php

namespace App\Modules\Maintenance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Response;
use ResourceHelper;
use ParameterHelper;

use App\Modules\Maintenance\Libraries\SystemGroupParser;

class SystemGroupController extends Controller
{

    /**
     * @var Object/Collection
     */
    private $system_group;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = ParameterHelper::validatePagination($request->all());
        $this->system_group = ResourceHelper::showAllResource('Maintenance', 'SystemGroup', (isset($pagination['page_size']) ? $pagination['page_size'] : 0), (isset($pagination['page']) ? $pagination['page'] : 0));
        return Response::json($this->system_group, $this->system_group['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->system_group = ResourceHelper::storeResource($request, 'Maintenance', 'SystemGroup', $request->all(), 1, 1);
        return Response::json($this->system_group, $this->system_group['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->system_group = ResourceHelper::showResource('Maintenance', 'SystemGroup', $id);
        return Response::json($this->system_group, $this->system_group['code']);
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
        $this->system_group = ResourceHelper::updateResource($request, 'Maintenance', 'SystemGroup', $id, $request->all(), 1, 1);
        return Response::json($this->system_group, $this->system_group['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->system_group = ResourceHelper::deleteResource($request, 'Maintenance', 'SystemGroup', $id, 1);
        return Response::json($this->system_group, $this->system_group['code']);
    }
    
    /**
     * Deactive system group id by batch as passed by parameter: 0 = DEACTIVATED
     * @param  Request $request 
     * @return json           
     */
    public function changeSystemGroupStatusByBatch(Request $request)
    {
        $this->system_group = SystemGroupParser::updateSystemGroupStatusByBatch($request, $request->get('system_group_id'), $request->get('is_active'));
        return Response::json($this->system_group, $this->system_group['code']);
    }
    
    /**
     * Delete system group by batch using passed parameter system_group_id array index
     * @param  Request $request 
     * @return json           
     */
    public function deleteSystemGroupByBatch(Request $request)
    {
        $this->system_group = SystemGroupParser::deleteSystemGroupByBatch($request, $request->get('system_group_id'));
        return Response::json($this->system_group, $this->system_group['code']);
    }
}
