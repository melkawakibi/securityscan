<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\PDFGenerator;

class PDFCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'download pdf';

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
        
        $path = storage_path('logs');
        
        $file = fopen($path . "/laravel.log","w");

        if($file !== false){
            ftruncate($file, 0);
            fclose($file);
        }

       new PDFGenerator;

    }
}