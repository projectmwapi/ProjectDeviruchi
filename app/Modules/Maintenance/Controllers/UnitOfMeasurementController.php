<?php

namespace App\Modules\Maintenance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Response;
use ResourceHelper;
use ParameterHelper;

class UnitOfMeasurementController extends Controller
{

    /**
     * @var Object/Collection
     */
    private $uom;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->uom = ResourceHelper::showAllResource('Maintenance', 'UnitOfMeasurement', ($request->has('page_size') ? $request->get('page_size') : 0), ($request->get('page') ? $request->get('page') : 0));
        return Response::json($this->uom, $this->uom['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->uom = ResourceHelper::storeResource($request, 'Maintenance', 'UnitOfMeasurement', $request->all(), 1, 1);
        return Response::json($this->uom, $this->uom['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->uom = ResourceHelper::showResource('Maintenance', 'UnitOfMeasurement', $id);
        return Response::json($this->uom, $this->uom['code']);
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
        $this->uom = ResourceHelper::updateResource($request, 'Maintenance', 'UnitOfMeasurement', $id, $request->all(), 1, 1);
        return Response::json($this->uom, $this->uom['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->uom = ResourceHelper::deleteResource($request, 'Maintenance', 'UnitOfMeasurement', $id, 1);
        return Response::json($this->uom, $this->uom['code']);
    }
}
