<?php

namespace App\Services;

use App\Model\Link;

class ServiceLink implements ServiceInterface{

	public function __construct(){

	}

	public function create($methode, $url, $id){
		
		$link = new Link;

		switch ($methode) {
			case 'GET':
				$link->methode = $methode;
				$link->url = $url;
				$link->website_id = $id;
				break;
			case 'POST':
				$link->methode = $methode;
				$link->url = $url;
				$link->website_id = $id;
				break;
		}

		$link->save();

	}

	public function findAll(){
		return Link::all();
	}

	public function findAllByWebsiteId($id){
		return Link::Where(['website_id' => $id])->get();
	}

	public function findOneById($var){

	}


	public function findOneByName($name){

	}


	public function numRowByName($name){
		
	}

}