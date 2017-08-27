<?php

namespace App\Services;

use App\Services\Service;
use App\HeaderLinkDAL;

class HeaderLinkService implements Service
{

	public static function store($object)
	{
		return HeaderLinkDAL::create($object);
	}

	public static function findAll()
	{
		return HeaderLinkDAL::findAll();
	}

	public static function findOneById($id)
	{
		return HeaderLinkDAL::findOneById($id);
	}

	public static function numRow($id)
	{
		return HeaderLinkDAL::numRow($id);
	}


}