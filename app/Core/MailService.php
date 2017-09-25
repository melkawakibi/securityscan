<?php

namespace App\Core;

use App\Mail\RegisterMail as Register;
use App\Mail\ActivationMail as Activation;
use App\Mail\ReportMail as Report;
use App\Services\ReportService;
use Mail;
use Log;

class MailService
{

	private static $email;

	public static function setEmail($customer){

		if(!is_null($customer->second_email)){
			MailService::$email = $customer->second_email;
		}else{
			MailService::$email = $customer->cms_email;
		}

	}

	public static function sendRegisterMail($customer)
	{
		try{
			\Mail::to(MailService::$email)->send(new Register($customer));
		}catch(\Exception $e){
			Log::info($e->getMessage());
		}
	}

	public static function sendActivationMail($customer)
	{
		try{
			\Mail::to(MailService::$email)->send(new Activation($customer));
		}catch(\Exception $e){
			Log::info($e->getMessage());
		}
	}

	public static function sendReportMail($report, $customer)
	{
		
		try{
			
			\Mail::to(MailService::$email)->send(new Report($report, $customer));
				
			$report->status = 1;
			ReportService::update($report);

		}catch(\Exception $e){
			Log::info($e->getMessage());
			$report->status = 0;
			ReportService::update($report);
		}

	}

}