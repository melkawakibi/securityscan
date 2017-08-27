<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\LinkDAL;

class LinkService implements Service
{

	public static function store($object)
	{
		LinkDAL::create($object);
	}

	public static function findAll()
	{
		LinkDAL::findAll();
	}

	public static function findOneById($id)
	{
		LinkDAL::findOneById($id);
	}

	public static function numRow($url)
	{
		return LinkDAL::numRowByUrl($url);
	}


}