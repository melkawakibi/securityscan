<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WebsiteService as Website;
use App\Services\CustomerService as Customer;
use \stdClass as Object;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {class}';

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

        $argument = $this->argument('class');

        if($argument === 'customer'){

            $customer = new Object;
            $customer->cms_id = 578;
            $customer->cms_name = 'Willem';
            $customer->cms_url = 'www.justbetter.nl';
            $customer->cms_email = 'Willem@justbetter.nl';
            $customer->active = 1;

            Customer::store($customer);

        }

        if($argument === 'website'){

            $website = new Object;
            $website->url = 'http://localhost:80';
            $website->server = 'apache 2.4';
            $website->follow_robot = 1;
            
            Website::store($website);
        }
       

    }
}
