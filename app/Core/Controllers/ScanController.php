<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerService as Customer;
use App\Scanner;
use \stdClass as Object;

class ScanController extends Controller
{

	public function start(Request $request)
	{

		return $request;

		// $customer = new Object;
  //       $customer->cms_id = 578;
  //       $customer->cms_name = 'Karel';
  //       $customer->cms_url = 'www.example.nl';
  //       $customer->cms_email = 'karel@example.nl';
  //       $customer->active = 1;

  //       Customer::store($customer);
	}

}