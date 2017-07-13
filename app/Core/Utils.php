<?php

namespace App\Core;



class Utils
{

	public static function headerToArray($header){

		$indexed = explode("\n", $header);
		$headers = array();

		foreach ($indexed as $key => $value) {
			array_push($headers, explode(":", $value));
		}

		return $headers;

	}

	public static function arrayBuilder($array){

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

		var_dump($array);

		return $array;

	}

	public function searchCriteria($inputValue, $findValues)
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


}