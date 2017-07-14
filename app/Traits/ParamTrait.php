<?php

namespace App\Traits;

use App\Model\Param;

trait ParamTrait{

	//Create headers
	public function createParams($id, $value){

		$param = new Param;
		$param->params = $value;
		$param->link_id = $id;

		$param->save();

		return $param;

	}

	public function findAll(){
		return Param::all();
	}

	public function findAllByLinkId($id){
		return Param::Where(['link_id' => $id])->get();
	}

	public function findOneById($id){
		return Param::Where(['id' => $id])->get();
	}


	public function numRowByParamAndLinkId($linkId, $param){
		return Param::Where(['link_id' => $linkId, 'params' => $param])->get()->count();
	}


	public function numRowByName($param){
		return Param::Where(['params' => $param])->get()->count();	
	}
}