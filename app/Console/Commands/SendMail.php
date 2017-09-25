<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\MailService as Mail;
use App\Model\Customer;
use \stdClass as Object;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $customer = new Object;
        $customer->name = 'example';
        $customer->company = 'example-company';
        $customer->second_email = 'example@email.com';
        $customer->cms_id = '145';
        $customer->cms_name = 'example';
        $customer->cms_url = 'http://localhost:8888';
        $customer->cms_email = 'example@email.nl';
        $customer->cms_register_date = '2017-09-04 17:00:00';
        $customer->active = 1;

        Mail::sendRegisterMail($customer);
    }
}
