<?php

namespace App\DAL;

use App\Model\Header;

class HeaderDAL
{

	public function createHeaders($object)
	{
		if(!is_null($object)){
			$header = new Header;
			$header->name = $object->headers[0];
			$header->value = $object->headers[1];
			$header->website_id = $object->website_id;

			$header->save();

			return $header;
		}
	}

	public function findAll()
	{
		return Header::all();
	}

	public function findOneById($id)
	{
		return Header::Where(['id' => $id])->get();
	}

	public function numRow($id)
	{
		return Header::Where(['website_id' => $id])->get()->count();
	}

	public function findAllByWebsiteId($id)
	{
		return Header::Where(['website_id' => $id])->get();
	}


}