<?php

namespace App\DAL;

use App\Model\Header;

class HeaderDAL
{

	public static function create($headers, $id)
	{

		if(!is_null($headers)){
			$header = new Header;
			$header->name = $headers[0];
			$header->value = $headers[1];
			$header->website_id = $id;

			$header->save();

			return $header;
		} 
	}

	public static function findAll()
	{
		return Header::all();
	}

	public static function findOneById($id)
	{
		return Header::Where(['id' => $id])->get();
	}

	public static function numRow($id)
	{
		return Header::Where(['website_id' => $id])->get()->count();
	}

	public static function update($object)
	{
		return $object->save();
	}

	public static function findAllByWebsiteId($id)
	{
		return Header::Where(['website_id' => $id])->get();
	}


}