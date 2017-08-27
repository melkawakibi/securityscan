<?php

namespace App\Services;

use App\Services\Service;
use App\DAL\CustomerDAL as Customer;


class CustomerService implements Service
{

	public static function store($object)
	{
		return Customer::create($object);
	}

	public static function findAll()
	{
		return Customer::findAll();
	}

	public static function findOne($id)
	{
		Customer::findCustomberByUrl($id);
	}

	public static function findOneByUrl($url)
	{
		Customer::findCustomberByUrl($url);
	}

	public static function numRow($var)
	{
		//TODO
	}		

}