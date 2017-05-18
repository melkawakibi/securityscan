<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Main;

class cmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan {url}';

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
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {
        if(!preg_match("[https://]", $this->argument('url'))){
            $url = "https://" . $this->argument('url');
        }else if(!preg_match("[http://]", $this->argument('url'))){
            $url = "http://" . $this->argument('url');
        }else if(!preg_match("[http://www.]", $this->argument('url'))){
            $url = "http://www." . $this->argument('url');
        }else{
            $url = $this->argument('url');
        }

        $main = new Main($url);
    }
}
