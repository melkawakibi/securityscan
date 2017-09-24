<?php

namespace App\Core\Http\Controllers;

use App\Services\ReportService as Report;
use Illuminate\Support\Facades\Response;
use File;

class PDFController extends Controller
{

	
	public function getReport()
	{
		$reports = Report::findAll();

		$file = $reports->first()->file;

	    $file = File::get($file);
	    $response = Response::make($file, 200);
	   
	    $response->header('Content-Type', 'application/pdf');

	    return $response;

	}
	
}