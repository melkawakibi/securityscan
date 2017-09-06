<?php

namespace App\Core;

use PHPCrawler;
use PHPCrawlerDocumentInfo;
use PHPCrawlerResponseHeader;
use App\Core\Crawler;
use App\Services\WebsiteService as Website;
use App\Services\HeaderService as Header;
use App\Services\LinkService as Link;
use App\Services\HeaderLinkService as HeaderLink;
use App\Services\ParamService as Param;
use App\Services\CustomerService as Customer;
use \stdClass as Object;
use Illuminate\Support\Facades\Log;
use App\Core\Utils;

class Spider extends PHPCrawler
{

	private $url;
	private $crawler;
	private $follow_robot;
	private $follow_mode;
	private $is_enabled_robot;
	private $headers = array();
	private $links;
	private $website_id;
	private $customer_id;

	public function __construct()
	{
		parent::__construct();

		$this->crawler = new Crawler;
	}

	public function setup($options)
	{

		$this->setURL($this->url);

		$this->customer_id = $this->getCustomerId($this->url);

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

		$this->handleWebsite($pageInfo);

		$this->handelParams($pageInfo);
	}

	public function handleWebsite($pageInfo)
	{

		$website = new Object;
		$website->url = $this->url;
		$website->server = Utils::getServer($this->headers);
		$website->follow_robot = $this->follow_robot;	
		$website->customer_id = $this->customer_id;	

		if(!is_null($website)){
			$website = Website::store($website);
			if(!is_null($website)){
				$this->setWebsite($website->id);
			}
		}

		if($this->url === $pageInfo->url){

			if(!is_null($website)){

				$header = new Object;
				$header->headers = $this->headers;
				$header->website_id = $this->website_id;

				Header::store($header);
			}

		}else{

			$this->handleLinks($this->links);

		}
	}

	public function handleLinks($links)
	{
		if(!empty($links)){
			
			foreach ($links as $link) {

				$link = (object) $link;

				if(!Link::numRow($link->url_rebuild)){

					$object = new Object;
					$object->link = $link;
					$object->website_id = $this->website_id;

					$link = Link::store($object);

					if(!is_null($link)){

						$headerLink = new Object;
						$headerLink->headers = $this->headers;
						$headerLink->link_id = $link->id;

						HeaderLink::store($headerLink);
					}
				}
			}
		}
	}

	public function handelParams($pageInfo)
	{

		$paramPOST = $this->crawler->getFormsParams($pageInfo->url);

		$paramGET = $this->crawler->getURIParams($pageInfo->url);

		if(!empty($paramGET)){
			foreach ($paramGET as $param) {

				if(!Param::numRow($param)){
					Param::store($param);
				}			
			}
		}
	}

	public function getCustomerId($url)
	{
		return Customer::findIdByUrl($this->url);
	}

	public function setSpiderConfig($url)
	{
		$this->url = $url;
	}

	public function setWebsite($id)
	{
		$this->website_id = $id;
	}

}