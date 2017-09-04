<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\Services\WebsiteService as Website;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\CustomerService as Customer;
use Illuminate\Support\Facades\Log;

class PDFGenerator
{

	public static function generatePDF($website)
	{

		$scanDetail = ScanDetail::findOneById($website->id);
		$customer = Customer::findOneById($website->base_url);

		$html = \PDF::parseView('template', ['scan_details' => $scanDetail, 'website' => $website, 'customer' => $customer]);

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