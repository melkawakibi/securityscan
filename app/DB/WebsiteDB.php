<?php

namespace App\DB;

use App\Model\Website;
use App\Traits\HeaderTrait;
use Carbon\Carbon;

class WebsiteDB{

	use HeaderTrait;

	public function __construct(){

	}

	//Create website
	public function create($websiteObj){

		if(strlen($websiteObj->url) < 255){
			$website = new Website;
			$website->base_url = $websiteObj->url;
			$website->server = $websiteObj->server;
			$website->date = Carbon::now();
			$website->customer_id = 1;

			$website->save();
		}

		return $website;
	}

	//Find all websites
	public function findAll(){

		return Website::all();

	}

	public function findOneById($var){
		
	}

	//Find website by url
	public function findOneByUrl($url){

		return Website::Where(['base_url' => $url])->get();

	}

	//returns num rows by url
	public function numRowByUrl($url){

		return Website::Where(['base_url' => $url])->get()->count();

	}

}