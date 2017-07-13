<?php

namespace App\Traits;

use App\Model\HeaderLink;

trait HeaderLinkTrait{

	//Create headers
	public function createHeaderLinks($url, $value, $id){

		$header = new HeaderLink;
		$header->name = $url;
		$header->value = $value;
		$header->link_id = $id;

		$header->save();

		return $header;

	}

	public function findAll(){
		return HeaderLink::all();
	}

	public function findAllByWebsiteId($id){
		return HeaderLink::Where(['link_id' => $id])->get();
	}

	public function findOneById($id){
		return HeaderLink::Where(['id' => $id])->get();
	}

}