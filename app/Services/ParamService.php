<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\ParamDal as Param;

class ParamService implements Service
{

	public static function store($object)
	{
		return Param::create($object);
	}

	public static function findAll()
	{
		return Param::findAll();
	}

	public static function findOneById($id)
	{
		return Param::findOneById($id);
	}

	public static function numRow($id)
	{
		return Param::numRow($id);
	}

	public static function numRowByName($param)
	{
		return Param::numRowByName($name);
	}

	public static function findAllByLinkId($id)
	{
		return Param::findAllByLinkId($id);
	}

}