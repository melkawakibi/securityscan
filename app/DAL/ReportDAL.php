<?php

namespace App\DAL;

use App\Model\Report;

class ReportDAL
{

	public static function create($object)
	{
		$report = new Report;
		$report->scan_id = $object->scan_id;
		$report->file = $object->file;
		$report->save();

	}

	public static function findAll()
	{
		return Report::all();
	}

	public static function findOneById($id)
	{
		return Report::Where(['id' => $id])->get();
	}

	public static function numRow($id)
	{
		return Report::Where(['id' => $id])->get()->count();
	}

	public static function update($object)
	{
		return $object->save();
	}

}