<?php

namespace App\DAL;

use App\Model\Customer;

class CustomerDAL
{

	public static function create($object)
	{

		if(strlen($object->cms_url) < 255){
			$customer = new Customer;
			$customer->cms_id = $object->cms_id;
			$customer->cms_name = $object->cms_name;
			$customer->cms_url = $object->cms_url;
			$customer->cms_email = $object->cms_email;
			$customer->active = $object->active;

			$customer->save();
		}

		return $customer;

	}

	public static function findAll()
	{
		return Customer::all();
	}

	public static function findOneById($id)
	{
		return Customer::Where(['id' => $id])->get();
	}

	public static function numRow($id)
	{
		return Customer::Where(['cms_id' => $id])->get()->count();
	}

	public static function findCustomerById($id)
	{
		return Customer::Where(['id' => $id])->get();
	}

	public static function findCustomberByUrl($url)
	{
		return Customer::Where(['cms_url' => $url])->get();
	}


}
