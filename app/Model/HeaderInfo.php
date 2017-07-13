<?php

namespace App\Model;

class HeaderInfo
{

	private $headers = array();

	public function __construct($headers){
		$this->headers = $headers;
	}

	public function getServer(){

		foreach ($this->headers as $key => $array) {

			foreach ($array as $key => $value) {

				if($array[0] === 'Server' || $array[0] === 'server'){
					return $array[1];
				}
			}
		}

		return "No-server-found";
	}

	public function getDate($haeders){
		
	}


}