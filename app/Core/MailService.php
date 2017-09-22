<?php

namespace App\Core;

use App\Mail\RegisterMail as Register;
use App\Mail\ActivationMail as Activation;
use App\Mail\ReportMail as Report;
use Mail;

class MailService
{

	public static function sendRegisterMail($customer)
	{
		\Mail::to($customer->cms_email)->send(new Register($customer));
	}

	public static function sendActivationMail($customer)
	{
		\Mail::to($customer[0]->cms_email)->send(new Activation($customer[0]));
	}

	public static function sendReportMail($report, $customer)
	{
		\Mail::to($customer[0]->cms_email)->send(new Report($report, $customer[0]));
	}

}