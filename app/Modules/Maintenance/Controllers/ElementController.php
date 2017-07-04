<?php

namespace App\Modules\Maintenance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Response;
use ResourceHelper;
use ParameterHelper;

class ElementController extends Controller
{

    /**
     * @var Object/Collection
     */
    private $element;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = ParameterHelper::validatePagination($request->all());
        $this->element = ResourceHelper::showAllResource('Maintenance', 'Element', (isset($pagination['page_size']) ? $pagination['page_size'] : 0), (isset($pagination['page']) ? $pagination['page'] : 0));
        return Response::json($this->element, $this->element['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->element = ResourceHelper::storeResource($request, 'Maintenance', 'Element', $request->all(), 1, 1);
        return Response::json($this->element, $this->element['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->element = ResourceHelper::showResource('Maintenance', 'Element', $id);
        return Response::json($this->element, $this->element['code']);
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
        $this->element = ResourceHelper::updateResource($request, 'Maintenance', 'Element', $id, $request->all(), 1, 1);
        return Response::json($this->element, $this->element['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->element = ResourceHelper::deleteResource($request, 'Maintenance', 'Element', $id, 1);
        return Response::json($this->element, $this->element['code']);
    }
}
