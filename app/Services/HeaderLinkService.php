<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\HeaderLinkDAL as HeaderLink;
use App\Core\Utils;

class HeaderLinkService implements Service
{

	public static function store($object)
	{

		foreach ($object->headers as $header) {
			$array = Utils::arrayBuilder($header);
			HeaderLink::create($array, $object->link_id);
		}
	}

	public static function findAll()
	{
		return HeaderLink::findAll();
	}

	public static function findOneById($id)
	{
		return HeaderLink::findOneById($id);
	}

	public static function numRow($id)
	{
		return HeaderLink::numRow($id);
	}

	public static function update($object)
	{
		return HeaderLink::update($object);
	}


}