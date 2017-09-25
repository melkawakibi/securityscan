<?php

namespace App\Core;

use App\Mail\RegisterMail as Register;
use App\Mail\ActivationMail as Activation;
use App\Mail\ReportMail as Report;
use App\Services\ReportService;
use Mail;

class MailService
{

	public static function sendRegisterMail($customer)
	{
		try{
			\Mail::to($customer->cms_email)->send(new Register($customer));
		}catch(\Exception $e){

		}
	}

	public static function sendActivationMail($customer)
	{
		try{
			\Mail::to($customer[0]->cms_email)->send(new Activation($customer[0]));
		}catch(\Exception $e){

		}
	}

	public static function sendReportMail($report, $customer)
	{
		try{
			\Mail::to($customer[0]->cms_email)->send(new Report($report, $customer[0]));
			
			$report->status = 1;
			ReportService::update($report);

		}catch(\Exception $e){
			$report->status = 0;
			ReportService::update($report);
		}

	}

}