<?php

namespace App\Core;

use App\Mail\RegisterMail as Register;
use Mail;

class MailService
{

	public static function sendRegisterMail()
	{
		\Mail::to('selkawakibi@gmail.com')->send(new Register);
	}


}