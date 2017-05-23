<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Main;

class cmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan {url} 
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
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle()
    {
        
        $url = $this->argument('url');

        $options = $this->options();

        new Main($url, $options);
    }
}
