<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\CustomerService as Customer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class PDFGenerator
{

	public static function generatePDF($website)
	{

		$scan = Scan::findOneByWebsiteId($website->id);

		$scanDetail = ScanDetail::findAllScanDetailsByScanId($scan[0]->id);

		$customer = Customer::findOneById($website->base_url);

		$html = View::make('template', ['website' => $website, 'scan' => $scan, 'scan_details' => $scanDetail, 'customer' => $customer])->render();

		try {
		    $html2pdf = new Html2Pdf('P', 'A4', 'en');
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($html);
		    $html2pdf->output('public/resources/reports/report.pdf', 'F');
		} catch (Html2PdfException $e) {
		    $formatter = new ExceptionFormatter($e);
		    echo $formatter->getHtmlMessage();
		}
	}
}