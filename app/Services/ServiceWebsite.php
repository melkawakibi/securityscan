<?php

namespace App\Services;

use App\Model\Website;
use App\Services\ServiceInterface;
use App\Traits\HeaderTrait;
use Carbon\Carbon;

class ServiceWebsite implements ServiceInterface{

	use HeaderTrait;

	public function __construct(){

	}

	//Create website
	public function create($url, $server){

		$website = new Website;
		$website->base_url = $url;
		$website->server = $server;
		$website->date = Carbon::now();
		$website->customer_id = 1;

		$website->save();

		return $website;
	}

	//Find all websites
	public function findAll(){

		return Website::all();

	}

	public function findOneById($var){
		
	}

	//Find website by name
	public function findOneByName($name){

		return Website::Where(['base_url' => $name])->get();

	}

	//returns num rows by name
	public function numRowByName($name){

		return Website::Where(['base_url' => $name])->get()->count();

	}

}