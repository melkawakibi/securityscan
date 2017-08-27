<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\HeaderDAL as Header;

class HeaderService implements Service
{

	public static function store($object)
	{
		foreach ($object->headers as $key => $array) {
			$array = Utils::arrayBuilder($array);
			HeaderDAL::createHeaders($array, $object->website_id);
		}
	}

	// foreach ($header->headers as $key => $array) {
	// 	$array = Utils::arrayBuilder($array);
	// 	$this->serviceLink->createHeaderLinks($array, $header->id);
	// }

	public static function findAll()
	{

	}

	public static function findOneById($var)
	{

	}

	public static function numRow($var)
	{

	}	


}