<?php

namespace App\Services;

use App\Model\Link;
use App\Traits\ParamTrait;
use Illuminate\Support\Facades\DB;

class ServiceLink implements ServiceInterface{

	use ParamTrait;

	private $link;

	public function __construct(){
		$this->link = new Link;
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
		
		return $link;
	}

	public function findAll(){
		return Link::all();
	}

	public function findAllByWebsiteId($id){
		return Link::Where(['website_id' => $id])->get();
	}

	public function findOneById($id){
		return Link::Where(['id' => $id])->get();
	}


	public function findOneByName($name){

	}


	public function numRowByName($name){
		
	}

}