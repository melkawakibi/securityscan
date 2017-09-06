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

	public static function update($object)
	{
		return Customer::update($object);
	}		

	public static function findOneByUrl($url)
	{
		return Customer::findOneByUrl($url);
	}

	public static function findIdByUrl($url)
	{
		if(Customer::numRowByUrl($url) > 0){
			return CustomerService::findOneByUrl($url)[0]->id;
		}
	}


}