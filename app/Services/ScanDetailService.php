<?php

namespace App\Services;

use App\DAL\ScanDetailDAL;

class ScanDetailService implements Service
{

	public static function store($object)
	{
		return ScanDetailDAL::create($object);
	}

	public static function findAll()
	{
		return ScanDetailDAL::findAll();
	}

	public static function findOneById($id)
	{
		return ScanDetailDAL::findOneById($id);
	}

	public static function numRow($id)
	{
		return ScanDetailDAL::numRow($id);
	}

	public static function findAllScanDetailsByScanId($id)
	{
		return ScanDetailDAL::findAllScanDetailsByScanId($id);
	}
}