<?php

namespace App\Libraries;

class StatusHelper
{

	/**
	 * Get custom error status response for each module
	 * @param  string $module 
	 * @return string
	 */
	public static function getErrorResponseStatus($module = '')
	{
		$response 	   = 'ER000';
		return $response;
	}

	/**
	 * Get custom success status response for each module
	 * @param  string $module 
	 * @return string
	 */
	public static function getSuccessResponseStatus($module = '')
	{
		$response 	   = 'SC001';
		return $response;
	}

	/**
	 * Get custom not found status response for each module
	 * @param  string $module 
	 * @return string
	 */
	public static function getNotFoundResponseStatus($module = '')
	{
		$response 	   = 'NF002';
		return $response;
	}

}