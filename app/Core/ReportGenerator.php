<?php

namespace App\Core;

use Barryvdh\DomPDF\Facade as PDF;
use App\Core\HtmlGenerator as Html;
use GuzzleHttp\Client as GuzzleClient;
use App\Http\Controllers\Controller;

class ReportGenerator extends Controller
{

	private $htmlGenerator;
	private $pdf;
	private $client;

	public function __construct()
	{	
		$this->client = new GuzzleClient;
		$this->htmlGenerator = new Html();
		$this->client->request('GET', 'http://localhost:8888/pdf');

	}

	public function generateReport()
	{

		$filename = $this->htmlGenerator->getFileName();

		$this->pdf = PDF::loadView($filename);
		return $this->pdf->download('report.pdf');

	}

}