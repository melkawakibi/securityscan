<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ClearTablesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete scannerdb data';

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
        //delete all data
        DB::table('params')->delete();    
        DB::table('headers')->delete();
        DB::table('header_links')->delete();
        DB::table('links')->delete();
        DB::table('scan_details')->delete();
        DB::table('reports')->delete();
        DB::table('scans')->delete();
        DB::table('websites')->delete();
        DB::table('customers')->delete();
        DB::table('users')->delete();

        echo 'All data cleared' . PHP_EOL;
    }
}
