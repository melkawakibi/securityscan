<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\CustomerService as Customer;
use \stdClass as Object;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class PDFGenerator
{

	/**
	 * 
	 * @param Website $website
	 * @return void
	 */
	public static function generatePDF($website)
	{

		$scan = Scan::findOneByWebsiteId($website->id);

		$scanDetail = ScanDetail::findAllScanDetailsByScanId($scan[0]->id);

		$customer = Customer::findOneByUrl($website->base_url);

		$risk = PDFGenerator::countRisks($scanDetail);

		$modules = PDFGenerator::countModules($scanDetail);

		$html = View::make('template', ['website' => $website, 'scan' => $scan[0], 'scandetails' => $scanDetail, 'risk' => $risk, 'modules' => $modules, 'customer' => $customer[0]])->render();

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

	/**
	 *  
	 * @param ScanDetail $object
	 * @return StdClass $riskObject
	 */
	public static function countRisks($object)
	{
		$riskObject = new Object;
		$riskObject->high = 0;
		$riskObject->average = 0;
		$riskObject->low = 0;

		foreach ($object as $value) {
			
			$risk = $value->risk;

			if($risk === 'high'){
				$riskObject->high += 1;
			}elseif ($risk === 'average') {
				$riskObject->average += 1;
			}else{
				$riskObject->low += 1;
			}
		}

		return $riskObject;
	}

	/**
	 * 
	 * @param ScanDetail $scanDetail
	 * @return StdClass $moduleObject
	 */
	public static function countModules($object)
	{	
		
		$moduleObject = new Object;
		$moduleObject->sql = array('module' => 'SQLI', 'risk' => 'hoog', 'count' => 0);
		$moduleObject->xss = array('module' => 'XSS', 'risk' => 'hoog', 'count' => 0);

		foreach ($object as $value) {
			
			$module = $value->module_name;

			if($module === 'sql'){
				$moduleObject->sql['count'] += 1;
			}else{
				$moduleObject->xss['count'] +=1;
			}

		}

		return $moduleObject;

	}

}