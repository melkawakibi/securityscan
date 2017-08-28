<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\ScanDAL as Scan;

class ScanService implements Service
{

	public static function store($object)
	{
		return Scan::create($object);
	}

	public static function findAll()
	{
		return Scan::findAll();
	}

	public static function findOneById($id)
	{
		return Scan::findOneById($id);
	}

	public static function numRow($id)
	{
		return Scan::numRow($id);
	}

	public static function findLastByScanIdOrderDesc($id)
	{
		return Scan::findLastByScanIdOrderDesc($id);
	}

}