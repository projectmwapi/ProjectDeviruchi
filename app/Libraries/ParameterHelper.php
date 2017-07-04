<?php

namespace App\Libraries;

use \Carbon\Carbon;

class ParameterHelper
{

	/**
	 * Validate if there's a start and limit parameter for pagination
	 * @param  array  $request 
	 * @return array
	 */
	public static function validatePagination($request = [])
	{
		$parameter = [];
		foreach ($request as $key => $value) {
			if ($key == 'page_size') {
				$parameter['page_size'] = $value;
			}
			if ($key == 'page') {
				$parameter['page'] = $value;
			}
		}
		return $parameter;
	}

	/**
	 * Prepare parameter for multiple storing of resource with timestamp
	 * @param  array  $request 
	 * @return array
	 */
	public static function prepareMultipleResource($request = [])
	{
		$parameters = [];
		foreach ($request as $param) {
			foreach ($param as $key => $value) {
				$resource[$key] = $value;
			}
			$resource['created_at'] = Carbon::now();
			$resource['updated_at'] = Carbon::now();
			$parameters[] = $resource;
		}	
		return $parameters;
	}

}