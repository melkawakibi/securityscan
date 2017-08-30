<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerService as Customer;
use App\Scanner;
use \stdClass as Object;

class ScanController extends Controller
{

	public function store(Request $request)
	{
  		$customer = new Object;
      $customer->cms_id = $request->input('cms_id');
      $customer->cms_name = $request->input('cms_username');
      $customer->cms_email = $request->input('cms_email');
      $customer->cms_url = $request->input('cms_url');
      $customer->cms_register_date = $request->input('cms_register_date');
      $customer->active = 0;

      Customer::store($customer);

      return 'Succesvol geregistreerd';
	}

  public function authenticate(Request $request)
  {
      
  }

  public function scan(Request $request)
  {

  }


}