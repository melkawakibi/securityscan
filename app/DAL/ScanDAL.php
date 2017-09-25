<?php

namespace App\DAL;

use App\Model\Scan;

class ScanDAL{

	public static function create($object)
	{
		
		if(!is_null($object)){
			$scan = new Scan;
			
			$scan->website_id = $object->website_id;

			$scan->save();
			
			return $scan;
		}
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

	public static function update($object)
	{
		return $object->save();
	}

	public static function findOneByWebsiteId($id)
	{
		return Scan::Where(['website_id' => $id])->get();
	}

	public static function findLastByScanIdOrderDesc($id)
	{
		return Scan::Where(['website_id' => $id])->orderBy('created_at', 'desc')->get(); 
	}

}