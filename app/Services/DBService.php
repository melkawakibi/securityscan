<?php

namespace App\Services;

use App\Model\HeaderInfo;
use App\DB\CustomerDB;
use App\DB\WebsiteDB;
use App\DB\LinkDB;
use App\DB\ScanDB;
use App\Core\Utils;
use \stdClass as Object;

class DBService
{

	private $serviceCustomer;
	private $serviceWebsite;
	private $serviceLink;
	private $serviceScan;
	private $id;
	private $isBaseHeader;

	public function __construct()
	{
		$this->serviceCustomer = new CustomerDB;
		$this->serviceWebsite = new WebsiteDB;
		$this->serviceLink = new LinkDB;
		$this->serviceScan = new ScanDB;
		$this->isBaseHeader = false;
	}


	public function store($baseUrl, $follow_robot, $url, $headers, $links)
	{

		$headerInfo = new HeaderInfo($headers);

		if($baseUrl === $url){
			$website = new Object;
			$website->url = $baseUrl;
			$website->follow_robot = $follow_robot;
			$website->server = $headerInfo->getServer();

			if(!$this->serviceWebsite->numRowByUrl($baseUrl)){

				$website = $this->serviceWebsite->create($website);

				$this->isBaseHeader = true;

				$this->storeHeader($headers, $website->id, $this->isBaseHeader);

			}else{
				
				foreach ($links as $key => $link) {

					$this->storeLinks($link, $website[0]->id, $headers);

				}
			}
		}
	}

	public function storeCustomer($name, $url, $email)
	{
		$customer = new Object;
		$customer->name = $name;
		$customer->url = $url;
		$customer->email = $email;

		return $this->serviceCustomer->create($customer);
	}

	public function storeScan($website_id)
	{

		return $this->serviceScan->create($website_id);

	}

	public function storeHeader($headers, $id, $isBaseHeader)
	{

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
	

	}

	public function storeParams($post, $get)
	{

		foreach ($get as $key => $param){

			$param = (object) $param;

			if(!$this->serviceLink->numRowByParamAndLinkId($param->id, $param->param)){
				$this->serviceLink->createParams($param->id, $param->param);
			}
		}

		foreach ($post as $key => $param){
			
			$param = (object) $param;

			if(!$this->serviceLink->numRowByParamAndLinkId($param->id, $param->param)){
				$this->serviceLink->createParams($param->id, $param->param);
			}
		}
	}

}