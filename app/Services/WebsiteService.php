<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\WebsiteDAL as Website;

class WebsiteService implements Service
{

	public static function store($object)
	{
		if(!Website::numRowByUrl($object->url)){

			return Website::create($object);
			
		}
	}

	public static function findAll()
	{
		return Website::findAll();
	}

	public static function findOneById($id)
	{
		return Website::findOneById($id);
	}

	public static function numRow($url)
	{
		return Website::numRow($url);
	}

	public static function update($object)
	{
		return Website::update($object);
	}

	public static function findOneByUrl($url)
	{
		return Website::findOneByUrl($url);
	}

	public static function numRowByUrl($url)
	{
		return Website::numRowByUrl($url);
	}
}