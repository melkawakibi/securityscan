<?php

namespace App\DAL;

use App\Model\Param;

class ParamDAL
{

	public static function create($array)
	{

		if(!empty($array)){
			$param = new Param;
			$param->link_id = $array['id'];
			$param->params = $array['param'];
			$param->method = $array['method'];
			$param->save();

			return $param;
		}

	}

	public static function findAll()
	{
		return Param::all();
	}

	public static function findOneById($id)
	{
		return Param::Where(['id' => $id])->get();
	}

	public static function numRow($param)
	{
		return Param::Where(['link_id' => $param['id'], 'params' => $param['param']])->get()->count();
	}

	public static function update($object)
	{
		return $object->save();
	}

	public static function findAllByLinkId($id)
	{
		return Param::Where(['link_id' => $id])->get();
	}

	public static function findAllByMethod($method)
	{
		return Param::Where(['method' => $method])->get();
	}

	public static function numRowByName($param)
	{
		return Param::Where(['params' => $param])->get()->count();	
	}

	public static function findAllParamByLinkAndMethod($id, $method)
	{
		return Param::Where(['link_id' => $id, 'method' => $method])->get();
	}
}