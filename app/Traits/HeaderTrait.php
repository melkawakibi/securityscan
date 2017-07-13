<?php

namespace App\Traits;

use App\Model\Header;

trait HeaderTrait{

	//Create headers
	public function createHeaders($headers, $id){

		if($headers !== null){
			$header = new Header;
			$header->name = $headers[0];
			$header->value = $headers[1];
			$header->website_id = $id;

			$header->save();

			return $header;
		}else{
			return '';
		}

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

}