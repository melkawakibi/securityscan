<?php

namespace App\DAL;

use App\Model\Scan;
use Carbon\Carbon;

class ScanDAL{

	public static function create($website_id)
	{
		
		$scan = new Scan;
		
		$scan->website_id = $website_id;

		$scan->save();
		
		return $scan;
	}

	public static function findAll()
	{
		return Scan::all();
	}

	public static function findOneById($id)
	{
		return Scan::Where(['id' => $id])->get();
	}

	public static function numRow($id)
	{
		return Scan::Where(['website_id' => $id])->get()->count();
	}

	public static function findLastByScanIdOrderDesc($id)
	{
		return Scan::Where('website_id', $id)->orderBy('created_at', 'desc')->get(); 
	}

}