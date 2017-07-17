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
		if(count($array) > 2){
			for ($i=1; $i < count($array); $i++) { 
				$value .= ' ' . $array[$i];
			}

			for ($i=0; $i < count($array); $i++) { 
				if($i > 0){
					unset($array[$i]);
				}
			}

			//pop last item, add item and reindex array
			array_pop($array);
			array_push($array, $value);
			$array = array_values($array);
		}

		if(count($array) === 1){
			array_unshift($array, "Status");
		}

		if($array[1] === ""){
			$array = null;
		}

		return $array;

	}

	public static function searchCriteria($inputValue, $findValues)
	{
		if(is_array($findValues)){
			$i = 0;
			foreach ($findValues as $values) {

				if(is_array($values)){
					$position = searchCriteria($inputValue, $values); 
				}else{
					 $postition = strpos($inputValue, $values);


					 if($postition !== false)
					 {
					 	$i++;
					 }

					 if(count($findValues) == $i){
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

		if(strpos($url, "?") !== false){
			$queryLine = explode("?", $url);
			
			if(strpos($queryLine[1], "&") !== false){
				$queries = explode("&", $queryLine[1]);

				foreach ($queries as $key => $query) {
					
					if(strpos($query, "=") !== false){
						$param = explode("=", $query);
						array_push($params, $param[0]);
					}	
				}
			}else{

				if(strpos($queryLine[1], "=") !== false){
					$param = explode("=", $queryLine[1]);
					array_push($params, $param[0]);
				}
			}
		}else{
			return null;
		}

		return $params;
	}

	public static function getBaseUrl($url) {
  		$result = parse_url($url);
  		return $result['scheme']."://".$result['host'].":".$result['port'];
	}


}