<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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
      $hash = md5( $request->input('cms_id').
      $request->input('cms_username').
      $request->input('cms_email').
      $request->input('cms_url').
      $request->input('cms_register_date') );

      $customers = Customer::findAll();

      foreach ($customers as $customer) {
      
        $stored_hash = substr($customer->cms_id, 4);

        if($stored_hash === $hash){
          return Response::json(['check' => 1, 'stored_hash' => $stored_hash, 'hash' => $hash]);
        }

      }

      return Response::json(['check' => 0, 'stored_hash' => $stored_hash, 'hash' => $hash]);

  }

  public function scan(Request $request)
  {

  }


}