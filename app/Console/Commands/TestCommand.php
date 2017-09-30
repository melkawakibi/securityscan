<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WebsiteService as Website;
use App\Services\CustomerService as Customer;
use \stdClass as Object;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Core\Spider;
use App\Scanner;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {option}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dit is voor het testen van processen';

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
     * @return Array
     */
    public function defaultOptions()
    {
        return array( "r" => "y", "fm" => "0", "s" => 1, "x" => 1 );
    }

    public function logController()
    {
        $path = storage_path('logs');
        
        $file = fopen($path . "/laravel.log","w");

        if($file !== false){
            ftruncate($file, 0);
            fclose($file);
        }
    }

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {
        
        $this->logController();

        $argument = $this->argument('option');

        if($argument === 'website'){

            $website = new Object;
            $website->url = 'http://localhost:80';
            $website->server = 'apache 2.4';
            $website->follow_robot = 1;
            
            Website::store($website);
        }

        if($argument === 'customer-list'){

            $customer = Customer::findOneById(15);

            print_r($customer[0]);

        }

        if($argument === 'customers'){

            $customers = Customer::findAll();

            print_r($customers);

        }

        if($argument === 'customer'){

            $customer = new Object;
            $customer->name = 'example';
            $customer->company = 'console';
            $customer->second_email = 'webapplicatietest@gmail.com';
            $customer->cms_id = '145';
            $customer->cms_name = 'example';
            $customer->cms_url = 'http://localhost:8888';
            $customer->cms_email = 'webapplicatietest@gmail.com';
            $customer->cms_register_date = '2017-09-04 17:00:00';
            $customer->active = 1;

            Customer::store($customer);

        }

        if($argument === 'customer-wp'){

            $customer = new Object;
            $customer->name = 'wp-example';
            $customer->company = 'console';
            $customer->second_email = 'webapplicatietest@gmail.com';
            $customer->cms_id = '147';
            $customer->cms_name = 'wp-example';
            $customer->cms_url = 'http://cms.local';
            $customer->cms_email = 'webapplicatietest@gmail.com';
            $customer->cms_register_date = '2017-09-04 17:00:00';
            $customer->active = 1;

            Customer::store($customer);

        }

        if($argument === 'scan'){

            $url = 'http://localhost:8888';

            $scanner = new Scanner($url, $this->defaultOptions(), new Spider($url));

            $scanner->scan();

        }
       

    }
}
