<?php

namespace App\Services;

use App\DAL\ScanDetailDAL as ScanDetail;

class ScanDetailService implements Service
{

	public static function store($object)
	{
		return ScanDetail::create($object);
	}

	public static function findAll()
	{
		return ScanDetail::findAll();
	}

	public static function findOneById($id)
	{
		return ScanDetail::findOneById($id);
	}

	public static function numRow($id)
	{
		return ScanDetail::numRow($id);
	}

	public static function update($object)
	{
		return ScanDetail::update($object);
	}

	public static function findAllScanDetailsByScanId($id)
	{
		return ScanDetail::findAllScanDetailsByScanId($id);
	}

	public static function numRowByScanIdAndModuleAndLinkAndMethod($object)
	{
		return ScanDetail::numRowByScanIdAndModuleAndLinkAndMethod($object);
	}
}