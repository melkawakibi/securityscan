<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\Services\WebsiteService as Website;
use App\Services\ScanDetailService as ScanDetail;
use Illuminate\Support\Facades\Log;

class PDFGenerator
{

	public static function generatePDF($id, $website)
	{

		$website = Website::findOneById($id);
		$scanDetail = ScanDetail::findOneById($website[0]->id);

		$html = \PDF::parseView('template', ['scan_detail' => $scanDetail, 'website' => $website]);

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