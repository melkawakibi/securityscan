<?php

namespace App\DAL;

use App\Model\Param;

class ParamDAL
{

	public static function createParams($id, $value){

		$param = new Param;
		$param->params = $value;
		$param->link_id = $id;

		$param->save();

		return $param;

	}

	public static function findAll(){
		return Param::all();
	}

	public static function findAllByLinkId($id){
		return Param::Where(['link_id' => $id])->get();
	}

	public static function findOneById($id){
		return Param::Where(['id' => $id])->get();
	}


	public static function numRowByParamAndLinkId($linkId, $param){
		return Param::Where(['link_id' => $linkId, 'params' => $param])->get()->count();
	}


	public static function numRowByName($param){
		return Param::Where(['params' => $param])->get()->count();	
	}


}