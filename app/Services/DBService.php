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
	private $id;
	private $isBaseHeader;

	public function __construct()
	{
		$this->serviceWebsite = new WebsiteDB;
		$this->serviceLink = new LinkDB;
		$this->serviceScan = new ScanDB;
		$this->isBaseHeader = false;
	}


	public function store($baseUrl, $url, $headers, $links)
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

				$this->isBaseHeader = true;

				$this->storeHeader($headers, $website->id, $this->isBaseHeader);

			}

		}

		if($this->serviceWebsite->numRowByUrl($baseUrl)){

			$website = $this->serviceWebsite->findOneByUrl($baseUrl);

			foreach ($links as $key => $link) {

				$this->storeLinks($link, $website[0]->id, $headers);

			}
		}
	}

	public function storeHeader($headers, $id, $isBaseHeader)
	{
		$this->isBaseHeader = false;

		if($isBaseHeader){
			foreach ($headers as $key => $array) 
			{

				$array = Utils::arrayBuilder($array);
				$this->serviceWebsite->createHeaders($array, $id);

			}
		}else{

			foreach ($headers as $key => $array) 
			{
				$array = Utils::arrayBuilder($array);
				$this->serviceLink->createHeaderLinks($array, $id);

			}
		}

	}

	public function storeLinks($link, $id, $headers)
	{

		$link = (object) $link;

		if(!$this->serviceLink->numRowByUrl($link->url_rebuild)){

			$link = $this->serviceLink->create($link, $id);

			$this->storeHeader($headers, $link->id, $this->isBaseHeader);
		}

	}

	public function storeParams($params)
	{

		foreach ($params as $key => $param) 
		{
			$param = (object) $param;
			if(!$this->serviceLink->numRowByParamAndLinkId($param->id, $param->param)){
				$this->serviceLink->createParams($param->id, $param->param);
			}
		}
	}

}