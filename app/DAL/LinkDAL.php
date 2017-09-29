<?php

namespace App\DAL;

use App\Model\Link;
use App\Core\Utils;

class LinkDAL
{

	public static function create($object, $id)
	{
		$linkObject =  $object->link;

		if(strlen($linkObject->url_rebuild) < 255 && !empty($linkObject)){

			$link = new Link;

			$link->method = $object->method;
			$link->url = $linkObject->url_rebuild;
			$link->refering_url = $linkObject->refering_url;
			$link->is_redirect = $linkObject->is_redirect_url;
			$link->depth = $linkObject->url_link_depth;
			$link->website_id = $id;

			$link->save();
		
			return $link;

		}
	}

	public static function findAll()
	{
		return Link::all();
	}

	public static function findOneById($id)
	{
		return Link::Where(['id' => $id])->get();
	}

	public static function numRow($url)
	{
		return Link::Where(['url' => $url])->get()->count();
	}

	public static function update($object)
	{
		return $object->save();
	}

	public static function findAllByWebsiteId($id)
	{
		return Link::Where(['website_id' => $id])->get();
	}

	public static function findOneByLinkUrl($url)
	{
		return Link::Where(['url' => $url])->get();
	}

	public static function numRowByLinkAndMethod($url, $method)
	{
		return Link::Where('url', $url)->Where('method', $method)->get()->count();
	}

}