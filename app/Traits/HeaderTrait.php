<?php

namespace App\Traits;

use App\Model\Header;

trait HeaderTrait{

	//Create headers
	public function createHeaders($url, $value, $id){

		$header = new Header;
		$header->name = $url;
		$header->value = $value;
		$header->website_id = $id;

		$header->save();

	}
	
}