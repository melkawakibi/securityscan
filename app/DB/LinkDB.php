<?php

namespace App\DB;

use App\Model\Link;
use App\Traits\ParamTrait;
use Illuminate\Support\Facades\DB;

class LinkDB{

	use ParamTrait;

	public function __construct(){

	}

	public function create($methode, $url, $wid){
		
		$link = new Link;

		switch ($methode) {
			case 'GET':
				$link->methode = $methode;
				$link->url = $url;
				$link->website_id = $wid;
				break;
			case 'POST':
				$link->methode = $methode;
				$link->url = $url;
				$link->website_id = $wid;
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


	public function numRowByUrl($url){
		return Link::Where(['url' => $url])->get()->count();
	}

}