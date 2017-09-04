<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\HeaderDAL as Header;
use App\Core\Utils;

class HeaderService implements Service
{

	public static function store($object)
	{
		foreach ($object->headers as $key => $array) {
			$array = Utils::arrayBuilder($array);
			Header::create($array, $object->website_id);
		}
	}

	public static function findAll()
	{
		return Header::findAll();
	}

	public static function findOneById($id)
	{
		return Header::findOneById($id);
	}

	public static function numRow($id)
	{
		return Header::numRow($id);
	}	


}