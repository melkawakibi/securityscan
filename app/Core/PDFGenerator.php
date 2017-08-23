<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\DB\WebsiteDB;
use App\DB\ScanDB;
use App\Model\ScanDetail;
use Illuminate\Support\Facades\Log;

class PDFGenerator
{

	private $websiteDB;
	private $linkDB;
	private $scanDB;

	public function __construct()
	{
		$this->websiteDB = new WebsiteDB;
		$this->scanDB = new ScanDB;
	}

	public function generatePDF($id, $website)
	{

		$scanDetails = $this->scanDB->findAllScanDetailsByScanId($id);

		$html = \PDF::parseView('template', ['scans' => $scanDetails, 'website' => $website]);

		Log::info($html);

		try {
		    $html2pdf = new Html2Pdf('P', 'A4', 'en');
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($html);
		    $html2pdf->output('resources/reports/report.pdf', 'F');
		} catch (Html2PdfException $e) {
		    $formatter = new ExceptionFormatter($e);
		    echo $formatter->getHtmlMessage();
		}
	}
}