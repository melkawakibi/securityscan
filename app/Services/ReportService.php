<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\ReportDAL as Report;

class ReportService implements Service
{

	public static function store($object)
	{
		return Report::create($object);
	}

	public static function findAll()
	{
		return Report::findAll();
	}

	public static function findOneById($id)
	{
		return Report::findOneById($id);
	}

	public static function numRow($id)
	{
		return Report::numRow($id);
	}

	public static function update($object)
	{
		return Report::update($object);
	}
}