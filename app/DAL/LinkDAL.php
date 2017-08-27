<?php

namespace App\DAL;

use App\Model\Link;
use App\Core\Utils;

class LinkDAL
{

	public static function create($object)
	{

		if(strlen($object->linksObject->url_rebuild) < 255){

			$link = new Link;

			$linkObject = $object->linkObject;

			$linkCode = $linkObject->linkcode;

			$isPost = Utils::searchCriteria($linkCode, array('form', 'method', 'post'));

			$methode =  ($isPost ? 'POST' : 'GET');

			$link->methode = $methode;
			$link->url = linkObject->url_rebuild;
			$link->refering_url = linkObject->refering_url;
			$link->is_redirect = linkObject->is_redirect_url;
			$link->depth = linkObject->url_link_depth;
			$link->website_id = $object->website_id;

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

		public static function findAllByWebsiteId($id)
	{
		return Link::Where(['website_id' => $id])->get();
	}

	public static function findAllByLinkUrl($url)
	{
		return Link::Where(['url' => $url])->get();
	}

}