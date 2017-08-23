<?php

namespace App;

use App\Core\Modules\BlindSQLModule as BlindSQL;
use App\Core\Modules\XSSModule as XSS;
use App\Core\Spider;
use App\Core\Utils;
use App\DB\WebsiteDB;
use App\Services\DBService;
use App\Core\PDFGenerator as PDF;
use Illuminate\Support\Facades\Log;

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
	 * [$pdf description]
	 * @var PDFGenerator
	 */
	protected $pdf;

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

		$this->websiteDB = new WebsiteDB;
		$this->service = new DBService;
		$this->pdf = new PDF;
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
			$this->spider->setSpiderUrl($this->url);
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

		//find website store scan
		$website = $this->websiteDB->findOneByUrl($this->url);
		$scan = $this->service->storeScan($website[0]->id);

		if ($this->sql instanceof BlindSQL) {
			$this->sql->start();
		}

		if ($this->xss instanceof XSS) {
			$this->xss->start();
		}



	}

	public function generateReport($id, $website)
	{
		$this->pdf->generatePDF($id);
	}

}