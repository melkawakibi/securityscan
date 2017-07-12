<?php

namespace App\Core;

use PHPCrawler;
use PHPCrawlerDocumentInfo;
use PHPCrawlerResponseHeader;

use App\Core\BaseClient;
use Symfony\Component\DomCrawler\Crawler;

use App\DB\WebsiteDB;
use App\DB\LinkDB;
use App\Services\LoginService;

use Illuminate\Support\Facades\Log;

class Spider extends PHPCrawler
{

	private $url;
	private $client;

	private $headers = array();

	public function __construct($url){
		parent::__construct();

		$this->client = new BaseClient();
		$this->url = $url;

	}

	public function setup(){

		$this->setURL($this->url);

		$this->addContentTypeReceiveRule("#text/html#"); 

		$this->addURLFilterRule("#\.(jpg|jpeg|gif|png|css|js)$# i"); 
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

		$this->headers = $this->headerToArray($pageInfo->header);

		$this->client->setContent($pageInfo->content);
		$this->client->setStatusCode($pageInfo->http_status_code);
		$this->client->setHeaders($this->headers);

	 	$this->parseHTMLDocument($pageInfo->url, $pageInfo->content);

	}

	public function parseHTMLDocument($url, $content){

		$crawler = $this->client->request('GET', $url);

		$crawler->filter('a')->each(function (Crawler $node, $i){
			echo $node->attr('href');
		});

	}

	public function headerToArray($header){

		return explode("\n", $header);

	}


}