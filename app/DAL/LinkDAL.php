<?php

namespace App\DAL;

use App\Model\Link;
use App\Core\Utils;

class LinkDAL
{

	public static function create($object, $id)
	{

		if(strlen($object->url_rebuild) < 255 && !empty($object)){

			$link = new Link;

			$linkCode = $object->linkcode;

			$isPost = Utils::searchCriteria($linkCode, array('form', 'method', 'post'));

			$methode =  ($isPost ? 'POST' : 'GET');

			$link->methode = $methode;
			$link->url = $object->url_rebuild;
			$link->refering_url = $object->refering_url;
			$link->is_redirect = $object->is_redirect_url;
			$link->depth = $object->url_link_depth;
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

}