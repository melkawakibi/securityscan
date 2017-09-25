<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DropTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'drop scannerdb tables';

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
        Schema::dropIfExists('scan_details');
        Schema::dropIfExists('params');
        Schema::dropIfExists('header_links');
        Schema::dropIfExists('headers');
        Schema::dropIfExists('links');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('scans');
        Schema::dropIfExists('websites');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('users');
        Schema::dropIfExists('migrations');

        echo 'All tables have been droped' . PHP_EOL; 
    }
}
