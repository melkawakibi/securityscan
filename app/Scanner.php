<?php

namespace App;

use App\Core\Modules\BlindSQLModule as BlindSQL;
use App\Core\Modules\XSSModule as XSS;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Core\PDFGenerator as PDF;
use \stdClass as Object;
use App\Core\Spider;
use App\Core\Utils;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Scanner
{
	/**
	 * [$url description]
	 * @var string
	 */
	protected $url;

	/**
	 * [$sql description]
	 * @var BlindSQLModule
	 */
	protected $sql;

	/**
	 * [$xss description]
	 * @var XSSModule
	 */
	protected $xss;

	/**
	 * [$credentials description]
	 * @var Array
	 */
	protected $credentials;

	/**
	 * [$spider description]
	 * @var Spider
	 */
	protected $spider;

	/**
	 * [$websiteDB description]
	 * @var WebsiteDB
	 */
	protected $websiteDB;

	/**
	 * [$service description]
	 * @var DBService
	 */
	protected $service;

	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @param string $url
	 * @param Array $options
	 * @param Spider
	 */
	public function __construct(
		$url, 
		$options,
		Spider $spider
	)
	{

		$this->url = $url;
		$this->spider = $spider;
		$this->options = $options;

		$this->setup($spider);

		$this->spider->start();

	}

	/**
	 * @return void
	 */
	public function setup()
	{
		try {

			$this->checkUrl();
			$this->spider->setSpiderConfig($this->url, $this->customer);
			$this->prepare($this->options);
			$this->spider->setup($this->options);

		} catch (Exception $e) {
			Log::Exception($e);
		}
	}

	/**
	 * @return void
	 */
	private function checkUrl()
	{
		try {
			if (filter_var($this->url, FILTER_VALIDATE_URL)) {
				echo 'Creating target: '.$this->url.PHP_EOL;
			}
		} catch (Exception $e) {
			throw new Exception('invalid url, try again');
			Log::Exception($e);
			exit;
		}
	}

	/**
	 * @param  Array $options 
	 * @return void
	 */
	public function prepare($options)
	{

		if ($options['s']) {
			$this->sql = new BlindSQL($this->url);
		}

		if ($options['x']) {
			$this->xss = new XSS($this->url);
		}
	}

	/**
	 * @return void
	 */
	public function scan()
	{

		$website = Website::findOneByUrl($this->url);

		$scan = new Object;
		$scan->website_id = $website[0]->id;

		$scan = Scan::store($scan);

		$type = new Object;
		$type->sql = false;
		$type->xss = false;

		if ($this->sql instanceof BlindSQL) {
			$type->sql = true;
			$this->sql->start();
		}

		if ($this->xss instanceof XSS) {
			$type->xss = true;
			$this->xss->start();
		}

		$isShortReport = (!empty($this->options['rt']) ? 1 : 0);

		$scan->report_type = ($isShortReport) ? 'Short Report' : 'Full Report';

		$this->storeType($type, $scan);

		$this->storeScanTime($scan);

		echo PHP_EOL . 'Generating report...' . PHP_EOL;

		$this->generateReport($website[0], $scan, $isShortReport);

		echo PHP_EOL . 'Report is generated.' . PHP_EOL . 'It is stored in the folder: public/resources/reports' . PHP_EOL;
	}

	/**
	 * 
	 *@param stdClass $type
	 *@param Scan $scan
	 *return void
	 */
	public function storeType($object, $scan)
	{
		$type = '';
		if($object->sql === true && $object->xss === true){
			$type = 'Full Scan';
		}elseif($object->sql === true && $object->xss === false){
			$type = 'SQLi';
		}elseif($object->sql === false && $object->xss === true){
			$type = 'XSS';
		}

		$scan->type = $type;

		Scan::Update($scan);
	}

	/**
	 * 
	 * @param Scan $scan
	 * @return void
	 */
	public function storeScanTime($scan)
	{
		$collection = Scan::findOneById($scan->id);

		$storedScan = $collection->first();

		$start_time = $storedScan['created_at'];

		$scan->time_end = Carbon::now()->toDateTimeString();

		$scan->time_taken = $start_time->diffInSeconds(Carbon::now());		

		if($scan->time_taken > 60){
			$time_taken = round(($scan->time_taken/60));
			$scan->time_taken = $time_taken . ' minuten';			
		}elseif ($scan->time_taken > 3600) {
			$time_taken = round(($scan->time_taken/3600));
			$scan->time_taken = $time_taken . ' hours';
		}else{
			$scan->time_taken .= ' secondes';
		}

		echo PHP_EOL . 'Start Time: ' . $start_time . PHP_EOL;
		echo 'End time: ' . $scan->time_end . PHP_EOL; 
		echo 'Time Taken: ' . $scan->time_taken . PHP_EOL . PHP_EOL; 

		Scan::update($scan);
	}

	/**
	 * calls generatePDF from PDFGenerator class
	 * @param  Website $website
	 * @return void
	 */
	public function generateReport($website, $scan, $isShortReport)
	{
		PDF::generatePDF($website, $scan, $isShortReport);
	}

}