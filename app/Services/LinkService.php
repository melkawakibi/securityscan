<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\LinkDAL as Link;

class LinkService implements Service
{

	public static function store($object)
	{
		return Link::create($object, $object->website_id);
	}

	public static function findAll()
	{
		return Link::findAll();
	}

	public static function findOneById($id)
	{
		return Link::findOneById($id);
	}

	public static function numRow($url)
	{
		return Link::numRow($url);
	}

	public static function update($object)
	{
		return Link::update($object);
	}

	public static function findAllByWebsiteId($id)
	{
		return Link::findAllByWebsiteId($id);
	}

	public static function findOneByLinkUrl($url)
	{
		return Link::findOneByLinkUrl($url);
	}

	public static function numRowByLinkAndMethod($url, $method)
	{
		return Link::numRowByLinkAndMethod($url, $method);
	}

}