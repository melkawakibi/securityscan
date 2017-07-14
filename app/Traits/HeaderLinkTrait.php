<?php

namespace App\Traits;

use App\Model\HeaderLink;

trait HeaderLinkTrait{

	//Create headers
	public function createHeaderLinks($headers, $id)
	{

		if($headers !== null){
			$header = new HeaderLink;
			$header->name = $headers[0];
			$header->value = $headers[1];
			$header->link_id = $id;

			$header->save();

			return $header;
		}else{
			return '';
		}
	}

	public function findAll()
	{
		return HeaderLink::all();
	}

	public function findAllByWebsiteId($id)
	{
		return HeaderLink::Where(['link_id' => $id])->get();
	}

	public function findOneById($id)
	{
		return HeaderLink::Where(['id' => $id])->get();
	}

}