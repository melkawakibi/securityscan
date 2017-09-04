<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CustomerService as Customer;
use Illuminate\Support\Facades\Redirect;
use View;

class AdminController extends Controller
{
	public function customerList()
	{
		$customers = Customer::findAll();

		return view('adminpanel', ['customers' => $customers]);

	}

	public function updateActiveState($id)
	{
		$customer =  Customer::findOneById($id);

		if($customer[0]->active){
			$customer[0]->active = 0;
		}else{
			$customer[0]->active = 1;
		}

		$customer[0]->save();
	}

}