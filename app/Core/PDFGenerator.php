<?php

namespace App\Core;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\CustomerService as Customer;
use App\Services\ReportService as Report;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use \stdClass as Object;
use App\Core\Utils;
use App\Core\MailService as Mail;

class PDFGenerator
{

	/**
	 * 
	 * @param Website $website
	 * @return void
	 */
	public static function generatePDF($website, $scan, $isShortReport)
	{

		$scanDetail = ScanDetail::findAllScanDetailsByScanId($scan->id);

		$customer = Customer::findOneByUrl($website->base_url);

		$risk = PDFGenerator::countRisks($scanDetail);

		$level = PDFGenerator::checkRiskLevel($risk);

		$modules = PDFGenerator::countModules($scanDetail);

		$isScanDetailEmpty = false;

		if($scanDetail->isEmpty()){
			$isScanDetailEmpty = true;
		}

		$html = View::make('template', ['website' => $website, 'scan' => $scan, 'scandetails' => $scanDetail, 'isScanDetailEmpty' => $isScanDetailEmpty, 'risk' => $risk, 'modules' => $modules, 'customer' => $customer[0], 'isShortReport' => $isShortReport, 'level' => $level])->render();

		if($customer[0]->cms_id === '145'){
			$reportPath =  Lang::get('string.report_path_test');
		}else{
			$reportPath =  Lang::get('string.report_path');
		}

		$file = 'report-' . $scan->created_at;

		$file = Utils::pdfFilenameFormat($file);

		try {
		    $html2pdf = new Html2Pdf('P', 'A4', 'en');
		    $html2pdf->pdf->SetDisplayMode('fullpage');
		    $html2pdf->writeHTML($html);
		    $html2pdf->output($reportPath.$file, 'F');
		} catch (Html2PdfException $e) {
		    $formatter = new ExceptionFormatter($e);
		    echo $formatter->getHtmlMessage();
		}

		$report = new Object;
		$report->scan_id = $scan->id;
		$report->file = Lang::get('string.report_path') . $file;

		PDFGenerator::handleReport($report);

		Mail::sendRegisterMail();

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

	public static function checkRiskLevel($risk)
	{
		$level = 0;

		if($risk->low > 10 && $risk->low < 10){
			$level = 1;
		}elseif ($risk->low >= 10 && $risk->average <= 10  && $risk->high <= 5) {
			$level = 2;
		}elseif ($risk->average >= 10 && $risk->high >= 5) {
			$level = 3;
		}elseif ($risk->high >= 10) {
			$level = 4;
		}

		return $level;
	}

	/**
	 * 
	 * @param ScanDetail $scanDetail
	 * @return StdClass $moduleObject
	 */
	public static function countModules($object)
	{	
		
		$moduleObject = new Object;
		$moduleObject->blindSql = array('module' => 'BlindSQLi', 'risk' => 'hoog', 'count' => 0); 
		$moduleObject->sql = array('module' => 'SQLi', 'risk' => 'hoog', 'count' => 0);
		$moduleObject->xss = array('module' => 'XSS', 'risk' => 'hoog', 'count' => 0);

		foreach ($object as $value) {
			
			$module = $value->module_name;

			if($module === 'blindsql'){
				$moduleObject->blindSql['count'] += 1;
			}elseif($module === 'sql'){
				$moduleObject->sql['count'] += 1;
			}else{
				$moduleObject->xss['count'] +=1;
			}

		}

		return $moduleObject;

	}

	public static function handleReport($object)
	{
		return Report::store($object);
	}

}