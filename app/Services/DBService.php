<?php

namespace App\Services;

use App\Model\HeaderInfo;

use App\Core\Utils;

use App\DB\WebsiteDB;
use App\DB\LinkDB;
use App\DB\ScanDB;

use \stdClass as Object;

class DBService
{

	private $serviceWebsite;
	private $serviceLink;
	private $serviceScan;

	public function __construct()
	{
		$this->serviceWebsite = new WebsiteDB;
		$this->serviceLink = new LinkDB;
		$this->serviceScan = new ScanDB;
	}


	public function storeWebsite($baseUrl, $url, $headers)
	{
		$headerInfo = new HeaderInfo($headers);

		if($baseUrl === $url){
			$website = new Object;
			$website->url = $baseUrl;
			$website->server = $headerInfo->getServer();

			if(!$this->serviceWebsite->numRowByUrl($baseUrl)){

				//Check if website exists
				//If so is it updated? Yes, update record
				$website = $this->serviceWebsite->create($website);

				$this->storeHeader($headers, $website->id);
			}
		}
	}

	public function storeHeader($headers, $id)
	{

		foreach ($headers as $key => $array) 
		{

			$array = Utils::arrayBuilder($array);
			$this->serviceWebsite->createHeaders($array, $id);

		}

	}


	public function storeLinks($links)
	{


	}

}