<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\DB\ScanDB;
use App\DB\WebsiteDB;
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
                            {--s : SQL module} 
                            {--x : XSS module}';

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
        return array( "r" => "y", "fm" => "0", "s" => 1, "x" => 1 );
    }

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {

        $path = storage_path('logs');
        
        $file = fopen($path . "/laravel.log","w");

        if($file !== false){
            ftruncate($file, 0);
            fclose($file);
        }

        $url = $this->argument('url');

        $hasValues = Utils::arrayHasValues($this->options());

        $options = ($hasValues) ? $this->options() : $this->defaultOptions();

        $scanner = new Scanner($url, $options, new Spider($url) ,new WebsiteDB, new ScanDB);
        $scanner->scan();
    }
}
