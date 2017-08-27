<?php

namespace App\Core;


class Utils
{

	public static function headerToArray($header)
	{

		$indexed = explode("\n", $header);
		$headers = array();

		foreach ($indexed as $key => $value) {
			array_push($headers, explode(":", $value));
		}

		return $headers;

	}

	public static function arrayBuilder($array)
	{

		$value = "";
		if (count($array) > 2) {
			for ($i=1; $i < count($array); $i++) { 
				$value .= ' ' . $array[$i];
			}

			for ($i=0; $i < count($array); $i++) { 
				if ($i > 0) {
					unset($array[$i]);
				}
			}

			//pop last item, add item and reindex array
			array_pop($array);
			array_push($array, $value);
			$array = array_values($array);
		}

		if (count($array) === 1) {
			array_unshift($array, "Status");
		}

		if ($array[1] === "") {
			$array = null;
		}

		return $array;

	}

	public static function searchCriteria($inputValue, $findValues)
	{
		if (is_array($findValues)) {
			$i = 0;
			foreach ($findValues as $values) {

				if (is_array($values)) {
					$position = searchCriteria($inputValue, $values); 
				} else {
					 $postition = strpos($inputValue, $values);


					 if ($postition !== false) {
					 	$i++;
					 }

					 if (count($findValues) == $i) {
					 	return true;
					 }
				}

			}

			return false;
			
		} else {
			return strpos($inputValue, $findValues);
		}
	}

	public static function filterGetUrl($url)
	{

		$params = array();

		if (strpos($url, "?") !== false) {
			$queryLine = explode("?", $url);
			
			if (strpos($queryLine[1], "&") !== false) {
				$queries = explode("&", $queryLine[1]);

				foreach ($queries as $key => $query) {
					
					if(strpos($query, "=") !== false){
						$param = explode("=", $query);
						array_push($params, $param[0]);
					}	
				}
			} else {

				if (strpos($queryLine[1], "=") !== false) {
					$param = explode("=", $queryLine[1]);
					array_push($params, $param[0]);
				}
			}
		} else {
			return null;
		}

		return $params;
	}

	public static function getBaseUrl($url) 
	{
  		$result = parse_url($url);
  		if(!empty($result['path'])){
  			return $result['scheme']."://".$result['host'].":".$result['port']."/".$result['path'];
		}else{
			return $result['scheme']."://".$result['host'].":".$result['port'];
		}
	}

	public static function arrayHasValues($array)
	{

		foreach ($array as $key => $value) {
			if (!empty($value)) {
				return true;
			}
		}

		return false;

	}


	public static function getParamArray($param_array)
	{	
		$array = array();

		foreach ($param_array as $key => $value) {
			array_push($array, $value->params);
		}

		return $array;
	}

	public static function create_comined_array($array1, $array2)
	{
		$newArray = array();

		foreach ($array1 as $key){
        	foreach ($array2 as $i => $value) {
            	$newArray[$i][$key] = $value;
         	}
    }

		return $newArray;
	}

	public static function replace_string_array($array, $str, $strToReplace)
	{

		foreach ($array as $key => $value) {
			if(strpos($value, $strToReplace) !== false){
				$array[$key] = str_replace($strToReplace, $str, $value);
			}
		}

		return $array;
	}

	public static function generateRandomString($length = 10) 
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    return $randomString;
	}

	public static function getServer($headers){

		foreach ($this->headers as $key => $array) {

			foreach ($array as $key => $value) {

				if($array[0] === 'Server' || $array[0] === 'server'){
					return $array[1];
				}
			}
		}

		return "No-server-found";
	}


}