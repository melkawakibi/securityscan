<?php

namespace App\DAL;

use App\Model\HeaderLink;

class HeaderLinkDAL
{

	public static function createHeaderLinks($object)
	{

		if(!is_null($object)){

			$headerLink = new HeaderLink;
			$headerLink->name = $object->headers[0];
			$headerLink->value = $object->headers[1];
			$headerLink->website_id = $object->website_id;

			$headerLink->save();

			return $headerLink;
		}
	}

	public static function findAll()
	{
		return HeaderLink::all();
	}

	public static function findOneById($id)
	{
		return HeaderLink::Where(['id' => $id])->get();
	}

	public function numRow($id)
	{
		return HeaderLink::Where(['website_id' => $id])->get()->count();
	}

	public static function findAllByWebsiteId($id)
	{
		return HeaderLink::Where(['link_id' => $id])->get();
	}

	public static function findAllByWebsiteId($id)
	{
		return HeaderLink::Where(['link_id' => $id])->get();
	}

}