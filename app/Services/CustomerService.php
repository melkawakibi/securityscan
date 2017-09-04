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

	public static function findOneById($id)
	{
		return Customer::findOneById($id);
	}

	public static function numRow($id)
	{
		return Customer::numRow($id);
	}		

	public static function findOneByUrl($url)
	{
		return Customer::findCustomberByUrl($url);
	}


}