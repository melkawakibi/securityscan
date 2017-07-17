<?php

namespace App\DB;

use App\Model\Link;
use App\Traits\ParamTrait;
use App\Traits\HeaderLinkTrait;
use Illuminate\Support\Facades\DB;

use App\Core\Utils;

class LinkDB{

	use ParamTrait, HeaderLinkTrait;

	public function __construct(){

	}

	public function create($linkObj, $id){

		$linkCode = $linkObj->linkcode;

		$link = new Link;

		if(strlen($linkObj->url_rebuild) < 255){

			$isPost = Utils::searchCriteria($linkCode, array('form', 'method', 'post'));

			if($isPost){

				$methode = 'POST';

			}else{

				$methode = 'GET';

			}

			switch ($methode) {
				case 'GET':
					$link->methode = $methode;
					$link->url = $linkObj->url_rebuild;
					$link->refering_url = $linkObj->refering_url;
					$link->is_redirect = $linkObj->is_redirect_url;
					$link->depth = $linkObj->url_link_depth;
					$link->website_id = $id;
					break;
				case 'POST':
					$link->methode = $methode;
					$link->url = $linkObj->url_rebuild;
					$link->refering_url = $linkObj->refering_url;
					$link->is_redirect = $linkObj->is_redirect_url;
					$link->depth = $linkObj->url_link_depth;
					$link->website_id = $id;
					break;
			}

			$link->save();
		
			return $link;

		}else{
			
			return null;

		}
	}

	public function findAll(){
		return Link::all();
	}

	public function findAllByWebsiteId($id){
		return Link::Where(['website_id' => $id])->get();
	}

	public function findAllByLinkUrl($url){
		return Link::Where(['url' => $url])->get();
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