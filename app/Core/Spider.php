<?php

namespace App\Core;

use PHPCrawler;
use PHPCrawlerDocumentInfo;
use PHPCrawlerResponseHeader;
use App\Model\Crawler;
use App\Services\WebsiteService;
use App\Services\HeaderService;
use App\Services\LinkService;
use \stdClass as Object;
use Illuminate\Support\Facades\Log;

class Spider extends PHPCrawler
{

	private $url;
	private $crawler;
	private $follow_robot;
	private $follow_mode;
	private $is_enabled_robot;
	private $headers = array();
	private $links;

	public function __construct()
	{
		parent::__construct();

		$this->crawler = new Crawler;
	}

	public function setup($options)
	{

		$this->setURL($this->url);

		if (!empty($options['r'])) {
			if ($options['r'] === 'y') {
					$this->follow_robot = $this->obeyRobotsTxt(TRUE);
					$this->is_enabled_robot = "On";
				} else if ($options['r'] === 'n') {
					$this->follow_robot = $this->obeyRobotsTxt(FALSE);
					$this->is_enabled_robot = "Off";
				} else {
					//default, if user fill somthing else then y or n
					$this->follow_robot = FALSE;
					$this->is_enabled_robot = "Off";
				}
		} else {
			//default
			$this->follow_robot = FALSE;
			$this->is_enabled_robot = "Off";
		}

		//TODO add option to tabel website database, make swtich case
		if (!empty($options['fm'])) {
			if($options['fm'] === '0') {
				$this->setFollowMode(0);
				$this->follow_mode = '0, follow every link';
			} else if($options['fm'] === '1') {
				$this->follow_mode = '1, only follow same domian';
				$this->setFollowMode(1);
			} else if($options['fm'] === '2') {
				$this->follow_mode = '2, only follow same host';
				$this->setFollowMode(2);	
			} else if($options['fm'] === '3') {
				$this->follow_mode = '3, only follow same root url';
				$this->setFollowMode(3);
			} else {
				$this->follow_mode = '0, follow every link';
				$this->setFollowMode(0);
			}
		} else {
			$this->follow_mode = '0, follow every link';
		}

		$this->addContentTypeReceiveRule("#text/html#"); 

		$this->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i"); 

		$this->addURLFilterRule("#\.(css|js)$# i"); 
	}

	public function start()
	{

		echo 'Starting spider' . PHP_EOL;
		echo 'Follow Robot.txt: ' . $this->is_enabled_robot.PHP_EOL;
		echo 'Follow Mode: ' . $this->follow_mode.PHP_EOL.PHP_EOL;

		//Start crawling
		$this->go();

		//TODO make table for report information
		$report = $this->getProcessReport();

		echo "Summary:". PHP_EOL; 
		echo "Links followed: ".$report->links_followed . PHP_EOL; 
		echo "Documents received: ".$report->files_received . PHP_EOL; 
		echo "Bytes received: ".$report->bytes_received." bytes". PHP_EOL; 
		echo "Process runtime: ".$report->process_runtime." sec" . PHP_EOL.PHP_EOL;

	}


	public function handleDocumentInfo(PHPCrawlerDocumentInfo $pageInfo)
	{
		$this->headers = Utils::headerToArray($pageInfo->header);

		$this->links = $pageInfo->links_found;

		$this->crawler->createCrawler($pageInfo);

		$this->handleWebsite();

		$this->handelParams($pageInfo->url);
	}

	public function handleWebsite()
	{
		if($this->url === $pageInfo->url){

			$website = new Object;
			$website->url = $this->url;
			$website->follow_robot = $this->follow_robot;
			$website->server = Utils::getServer($this->headers);

			$website = WebsiteService::store($website);

			if(!is_null($website)){
				$headers = new Object;
				$headers->headers = $this->headers;
				$headers->website_id = $website[0]->id;

				HeaderService::store($headers);

				$links = new Object;
				$links->linksArray = $this->links;
				$links->website_id = $website[0]->id;

				$this->handleLinks($links);

			}
		}
	}

	public function handleLinks($links)
	{
		if(!empty($links->linksArray)){

			$links->linksObject = (object) $links->linksArray;
			
			if(LinkService::numRowByUrl($links->linksObject->url_rebuild)){

				$links = LinkService::store($links);

				if(!is_null($links)){
					$this->storeHeader($headers, $link->id, $this->isBaseHeader);
				}
			}
		}
	}

	public function handelParams($url)
	{

		$paramPOST = $this->crawler->getFormsParams($url);

		$paramGET = $this->crawler->getURIParams($url);

		$this->DBService->storeParams($paramPOST, $paramGET);
	}

	public function setSpiderUrl($url)
	{
		$this->url = $url;
	}


}