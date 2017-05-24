<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Main;

class deletedata extends Command
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
    protected $description = 'delete scandb data';

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
        //delte all data
        DB::table('params')->delete();    
        DB::table('headers')->delete();
        DB::table('links')->delete();
        DB::table('websites')->delete();
    }
}
