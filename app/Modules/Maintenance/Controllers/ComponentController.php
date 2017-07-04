<?php

namespace App\Modules\Maintenance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Response;
use ResourceHelper;
use ParameterHelper;

class ComponentController extends Controller
{

    /**
     * @var Object/Collection
     */
    private $component;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = ParameterHelper::validatePagination($request->all());
        $this->component = ResourceHelper::showAllResource('Maintenance', 'Component', (isset($pagination['page_size']) ? $pagination['page_size'] : 0), (isset($pagination['page']) ? $pagination['page'] : 0));
        return Response::json($this->component, $this->component['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->component = ResourceHelper::storeResource($request, 'Maintenance', 'Component', $request->all(), 1, 1);
        return Response::json($this->component, $this->component['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->component = ResourceHelper::showResource('Maintenance', 'Component', $id);
        return Response::json($this->component, $this->component['code']);
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
        $this->component = ResourceHelper::updateResource($request, 'Maintenance', 'Component', $id, $request->all(), 1, 1);
        return Response::json($this->component, $this->component['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->component = ResourceHelper::deleteResource($request, 'Maintenance', 'Component', $id, 1);
        return Response::json($this->component, $this->component['code']);
    }
    
}
