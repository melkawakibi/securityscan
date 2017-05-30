<?php

namespace App\Traits;

use App\Model\Header;

trait HeaderTrait{

	//Create headers
	public function createHeaders($url, $value, $id){

		$header = new Header;
		$header->name = $url;
		$header->value = $value;
		$header->website_id = $id;

		$header->save();

		return $header;

	}

	public function findAll(){
		return Header::all();
	}

	public function findAllByWebsiteId($id){
		return Header::Where(['website_id' => $id])->get();
	}

	public function findOneById($id){
		return Header::Where(['id' => $id])->get();
	}


	public function findOneByName($name){

	}


	public function numRowByName($name){
			
	}
}