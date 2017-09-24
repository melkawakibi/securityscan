<?php

namespace App\Core\Http\Controllers;

use App\Services\CustomerService as Customer;
use App\Services\ReportService as Report;
use Illuminate\Support\Facades\Redirect;
use View;
use App\Core\MailService as Mail;
use Illuminate\Http\Request;

class AdminController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
	{
		return view('admin.adminpanel');

	}

	public function showCustomers()
	{
		$customers = Customer::findAll();

		return view('admin.customers', ['customers' => $customers]);
	}

	public function showReports()
	{
		$reports = Report::findAll();
		$array = array();

		foreach ($reports as $report) {
			
			$customer = Customer::findCustomerByScanId($report->first()->scan_id);

			$array[] = ['name' => $customer->name, 'company' => $customer->company, 'report' => $report->file];
		
		}	

		return view('admin.reports', ['reports' => $array]);

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

		if($customer[0]->active){
			Mail::sendActivationMail($customer);
		}
	}

}