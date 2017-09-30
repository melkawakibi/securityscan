<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Services\CustomerService as Customer;
use App\Core\Spider;
use App\Scanner;
use App\Core\Utils;

class ScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan {url}
                            {--r= : y or n | to follow or not to follow the robot.txt} 
                            {--fm= : set follow mode}
                            {--u= : username} 
                            {--p= : password} 
                            {--bs : Blind SQL module}
                            {--s : SQL module} 
                            {--x : XSS module}
                            {--h : Header module}
                            {--rt : handles report type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan website';

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
        return array( "r" => "y", "fm" => "0", "bs" => 1, "s" => 1, "x" => 1, "h" => 1, "rt" => 0 );
    }

    public function checkOptionValue($options)
    {
        $array = array();
        foreach ($options as $key => $value) {
           if(!empty($value)){
                $array[$key] = $value;
           }
        }

        return $array;
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

        $url = $this->argument('url');

        $hasValues = Utils::arrayHasValues($this->options());

        $options = $this->checkOptionValue($this->options());

        if($hasValues){
            if(count($options) === 1){
                if($options['rt']){
                    $options = array( "r" => "y", "fm" => "0", "bs" => 1, "s" => 1, "x" => 1, "h" => 1, "rt" => 1 );
                }
            }else{
                $options = $this->options();
            }
        }else{
            $options = $this->defaultOptions();
        }

        $scanner = new Scanner($url, $options, new Spider($url));

        $scanner->scan();
    }
}
