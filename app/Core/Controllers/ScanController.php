<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;
use App\Services\CustomerService as Customer;
use App\Scanner;
use \stdClass as Object;
use \Artisan;
use App\Core\MailService as Mail;

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

      $customer = Customer::store($customer);

      Mail::sendRegisterMail($customer);

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

      if($customers->isEmpty()){
        return Response::json([
          'state' => 0 
        ]);
      }

      $active = 0;
      $stored_hash = '';

      foreach ($customers as $customer) {
        
        $stored_hash = substr($customer->cms_id, 4);

        if($stored_hash === $hash){
          $active = $customer->active;
          
          return Response::json([
            'state' => 1, 
            'stored_hash' => $stored_hash, 
            'hash' => $hash,
            'active' => $active
            ]);
        }

      }

      return Response::json([
        'state' => 1, 
        'stored_hash' => $stored_hash, 
        'hash' => $hash,
        'active' => $active
        ]);

  }

  public function setupScan(Request $request)
  {

    $collection = Customer::findOneByUrl($request->input('cms_url'));
    $customer = $collection[0];
    echo $customer->cms_url; 

    if(!empty($request->input('request_name'))){
      $request_name = $request->input('request_name');

      $this->caching($request_name, 'name');

    }else if(!empty($request->input('request_email'))){
      $request_email = $request->input('request_email');

      $this->caching($request_email, 'email');
    }

      $selectType = $request->input('type');

      $selectReport = $request->input('report');

      $this->scan($request, $selectReport, $selectType, $customer);

  }

  public function scan($request, $selectReport, $selectType, $customer)
  {

    if($selectReport === 'full-report'){
      switch ($selectType) {
        case 'full':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--rt' => 0]);
          return $request;

          break;

        case 'BlindSQL':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--bs' => 1, '--rt' => 0]);
          return $request;

          break;

        case 'SQL':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--s' => 1, '--rt' => 0]);
          return $request;

          break;

        case 'XSS':

          \Artisan::call('scan', ['url' => $customer->cms_url, '--x' => 1, '--rt' => 0]);
          return $request;

          break;
      }

    }elseif($selectReport === 'short-report'){
      switch ($selectType) {
        case 'full':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--rt' => 1]);
          return $request;

          break;

        case 'BlindSQL':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--bs' => 1, '--rt' => 1]);
          return $request;

          break;        

        case 'SQL':
          
          \Artisan::call('scan', ['url' => $customer->cms_url, '--s' => 1, '--rt' => 1]);
          return $request;

          break;

        case 'XSS':

          \Artisan::call('scan', ['url' => $customer->cms_url, '--x' => 1, '--rt' => 1]);
          return $request;

          break;

      }      
    }

  }

  public function caching($data, $key)
  {
      Cache::put($key, $data, 1440);
  }

}