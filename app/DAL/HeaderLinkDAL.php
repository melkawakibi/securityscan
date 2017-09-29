<?php

namespace App\DAL;

use App\Model\HeaderLink;

class HeaderLinkDAL
{

	public static function create($array, $id)
	{

		if(!is_null($array)){

			$headerLink = new HeaderLink;
			$headerLink->name = $array[0];
			$headerLink->value = $array[1];
			$headerLink->link_id = $id;

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

	public static function update($object)
	{
		return $object->save();
	}

	public static function findAllByLinkId($id)
	{
		return HeaderLink::Where(['link_id' => $id])->get();
	}

}