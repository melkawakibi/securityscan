<?php

namespace App\Traits;

use App\Model\Param;

trait ParamTrait{

	//Create headers
	public function createParams($value, $id){

		$param = new Param;
		$param->params = $value;
		$param->link_id = $id;

		$param->save();

		return $param;

	}
	
}