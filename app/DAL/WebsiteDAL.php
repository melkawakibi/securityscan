<?php

namespace App\DAL;

use App\Model\Website;

class WebsiteDAL
{

	public static function create($websiteObj)
	{
		if(strlen($websiteObj->url) < 255){
			$website = new Website;
			$website->base_url = $websiteObj->url;
			$website->server = $websiteObj->server;
			$website->follow_robot = $websiteObj->follow_robot;
			$website->customer_id = 1;

			$website->save();

			return $website;
		}
	}

	public static function findAll()
	{
		return Website::all();
	}

	public static function findById($id)
	{
		return Website::Where(['id' => $id])->get();
	}

	public static function findOneByUrl($url)
	{
		return Website::Where(['base_url' => $url])->get();
	}

	public static function numRowByUrl($url)
	{
		return Website::Where(['base_url' => $url])->get()->count();
	}

}