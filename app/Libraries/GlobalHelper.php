<?php

namespace App\Libraries;

use StatusHelper;
use \Carbon\Carbon;

use App\Modules\Utility\Models\Configuration;

class GlobalHelper
{

	/**
	 * Get configuration by specific variable
	 * @param  string $variable 
	 * @return json
	 */
	public static function getConfigurationByVariable($variable = '')
	{
		$configuration = Configuration::whereConfiguration($variable)->first();
		if ($configuration) {
			$response = $configuration->value;
		}
		else {
			$response = null;
		}
		return $response;
	}

	/**
	 * Get configuration values by config type that will be grouped into an array with key-value-pair
	 * @param  integer $config_type 
	 * @return array
	 */
	public static function getConfigurationByConfigTypeGroup($config_type = 0)
	{
		$configuration = Configuration::whereConfigType($config_type)->get();
		if (count($configuration)) {
			foreach ($configuration as $config) {
				$config_response[$config->configuration] = $config->value;
			}
			$response = $config_response;
		}
		else {
			$response = null;
		}	
		return $response;
	}

}