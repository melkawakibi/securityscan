<?php

namespace App\Core;

use PHPCrawler;
use PHPCrawlerDocumentInfo;
use PHPCrawlerResponseHeader;

use App\Model\Crawler;

use App\Services\DBService;

use Illuminate\Support\Facades\Log;

class Spider extends PHPCrawler
{

	private $url;
	private $client;
	private $DBService;
	private $crawler;
	private $followRobot;

	private $headers = array();

	public function __construct($url)
	{
		parent::__construct();

		$this->DBService = new DBService;
		$this->crawler = new Crawler;

		$this->url = $url;

	}

	public function setup($options)
	{

		$this->setURL($this->url);

		if($options['r'] === 'y'){
			$this->obeyRobotsTxt(TRUE);
			$this->followRobot = "On";
		}else if($options['r'] === 'n'){
			$this->obeyRobotsTxt(FALSE);
			$this->followRobot = "Off";
		}else{
			//default
			$this->followRobot = FALSE;
		}

		$this->addContentTypeReceiveRule("#text/html#"); 

		$this->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i"); 

		$this->addURLFilterRule("#\.(css|js)$# i"); 
	}

	public function start()
	{

		echo 'Starting spider' . PHP_EOL;
		echo 'Follow Robot.txt: ' . $this->followRobot.PHP_EOL;

		//Start crawling
		$this->go();

		$report = $this->getProcessReport();

		echo "Summary:". PHP_EOL; 
		echo "Links followed: ".$report->links_followed . PHP_EOL; 
		echo "Documents received: ".$report->files_received . PHP_EOL; 
		echo "Bytes received: ".$report->bytes_received." bytes". PHP_EOL; 
		echo "Process runtime: ".$report->process_runtime." sec" . PHP_EOL;

	}


	public function handleDocumentInfo(PHPCrawlerDocumentInfo $pageInfo)
	{
		$this->headers = Utils::headerToArray($pageInfo->header);

		$links = $pageInfo->links_found;

		$this->crawler->createCrawler($pageInfo);

		//Store all general information
		$this->DBService->store($this->url, $pageInfo->url, $this->headers, $links);

		//Store all params
		$this->handelParams($pageInfo->url);
	}

	public function handelParams($url)
	{
		$paramPOST = $this->crawler->getFormsParams($url);

		$paramGET = $this->crawler->getURIParams($url);

		$this->DBService->storeParams($paramPOST, $paramGET);
	}


}