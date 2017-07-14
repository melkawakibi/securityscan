<?php

namespace App\Core;

use PHPCrawler;
use PHPCrawlerDocumentInfo;
use PHPCrawlerResponseHeader;

use App\Core\BaseClient;
use Symfony\Component\DomCrawler\Crawler;

use App\Services\DBService;

use Illuminate\Support\Facades\Log;

class Spider extends PHPCrawler
{

	private $url;
	private $client;
	private $DBService;

	private $headers = array();

	public function __construct($url){
		parent::__construct();

		$this->client = new BaseClient();
		$this->DBService = new DBService;

		$this->url = $url;

	}

	public function setup(){

		$this->setURL($this->url);

		$this->addContentTypeReceiveRule("#text/html#"); 

		$this->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i"); 

		$this->addURLFilterRule("#\.(css|js)$# i"); 
	}

	public function start(){

		$this->setup();

		echo 'Starting spider' . PHP_EOL;
		$this->go();

		$report = $this->getProcessReport();

		echo "Summary:". PHP_EOL; 
		echo "Links followed: ".$report->links_followed . PHP_EOL; 
		echo "Documents received: ".$report->files_received . PHP_EOL; 
		echo "Bytes received: ".$report->bytes_received." bytes". PHP_EOL; 
		echo "Process runtime: ".$report->process_runtime." sec" . PHP_EOL;

	}


	public function handleDocumentInfo(PHPCrawlerDocumentInfo $pageInfo){

		$this->headers = Utils::headerToArray($pageInfo->header);

		$links = $pageInfo->links_found;

		$this->client->setContent($pageInfo->content);
		$this->client->setStatusCode($pageInfo->http_status_code);
		$this->client->setHeaders($this->headers);


		$this->parseHTMLDocument($pageInfo->url, $pageInfo->content);

		$this->DBService->store($this->url, $pageInfo->url, $this->headers, $links);


	}

	public function parseHTMLDocument($url, $content){

		$crawler = $this->client->request('GET', $url);

	}

	public function getForms(){

		$this->submits = array();
		$this->forms = array();

		$this->request->filter('input[type=submit]')->each(function($node){

			array_push($this->submits, $node->attr('value'));

		});

		foreach ($this->submits as $key => $value) {
			//echo $value.PHP_EOL;
			$this->forms[] = $this->request->selectButton($value)->form();
		}

		return $this->forms;
	}

}