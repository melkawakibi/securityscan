<?php

namespace App\DAL;

use App\Model\ScanDetail;

class ScanDetailDAL
{
	
	public static function create($scan_id, $properties)
	{

		$scanDetail = new ScanDetail;

		$scanDetail->scan_id = $scan_id;	
		$scanDetail->module_name = $properties['module_name'];
		$scanDetail->risk = $properties['risk'];
		$scanDetail->parameter = $properties['parameter'];
		$scanDetail->attack = $properties['attack'];
		$scanDetail->error = $properties['error'];
		$scanDetail->wasc_id = $properties['wasc_id'];
		$scanDetail->execution_time = $properties['execution_time'];

		$scanDetail->save();
		return $scanDetail;
	}

	public static function findAll()
	{
		return ScanDetail::all();
	}

	public static function findOneById($id)
	{
		return ScanDetail::Where(['id' => $id])->get();
	}

	public static function numRow($id)
	{
		return ScanDetail::Where(['id' => $id])->get()->count();
	}

	public static function findAllScanDetailsByScanId($id)
	{
		return ScanDetail::Where(['scan_id' => $id])->get();
	}


}