<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;s
use App\Services\CustomerService as Customer;
use App\Scanner;
use \stdClass as Object;

class ScanController extends Controller
{

	public function store(Request $request)
	{
  		$customer = new Object;
      $customer->name = $request->input('register_name');
      $customer->company = $request->input('register_company');
      $customer->second_email = $request->input('register_email');
      $customer->cms_id = $request->input('cms_id');
      $customer->cms_name = $request->input('cms_username');
      $customer->cms_email = $request->input('cms_email');
      $customer->cms_url = $request->input('cms_url');
      $customer->cms_register_date = $request->input('cms_register_date');
      $customer->active = 0;

      Customer::store($customer);
	}

  public function authenticate(Request $request)
  {
      $hash = md5( 
        $request->input('cms_id').
        $request->input('cms_username').
        $request->input('cms_email').
        $request->input('cms_url').
        $request->input('cms_register_date'));

      $customers = Customer::findAll();

      $stored_hash = '';

      foreach ($customers as $customer) {
      
        $stored_hash = substr($customer->cms_id, 4);

        if($stored_hash === $hash){
          return Response::json(
            ['check' => 1, 
            'stored_hash' => $stored_hash, 
            'hash' => $hash, 
            'active' => $customer->active]
            );
        }

      }

      return Response::json(['check' => 0, 'stored_hash' => $stored_hash, 'hash' => $hash]);

  }

  public function scan(Request $request)
  {

    if(!empty($request->input('request_name'))){
      $request_name = $request->input('request_name');

      $this->caching($request_name, 'name');

    }else if(!empty($request->input('request_email'))){
      $request_email = $request->input('request_email');

      $this->caching($request_email, 'email');
    }

      $select = $request->input('type');

      switch ($select) {
        case 'full':
          
          return 'full scan';

          break;
        
        case 'SQLi':
          
          return 'sqli';

          break;

        case 'XSS':

          return 'xss';

          break;

      }

  }

  public function caching($data, $key)
  {
      Cache::put($key, $data, 1440);
  }

}