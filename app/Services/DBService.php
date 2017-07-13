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

	public function __construct()
	{
		$this->serviceWebsite = new WebsiteDB;
		$this->serviceLink = new LinkDB;
		$this->serviceScan = new ScanDB;
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

				$this->storeHeader($headers, $website->id);

			}

		}

		if($this->serviceWebsite->numRowByUrl($baseUrl)){

			$website = $this->serviceWebsite->findOneByUrl($baseUrl);

			foreach ($links as $key => $link) {

				$this->storeLinks($link, $website[0]->id);

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


	public function storeLinks($link)
	{

		$link = (object) $link;

		$link = $this->serviceLink->create($link, $website->id);

	}

	public function storeParams($forms)
	{

		if(!empty($forms)){

			foreach ($forms as $form) {

				$fields = $form->all();

					if(!empty($fields)){

					if($form->getUri() === $link->url){

						foreach ($fields as $field) {

							$fieldObj = new Field($field);

							$type = $fieldObj->getType();

							if($type === 'text' || $type=== 'password'){
								
							}
						}
					}
				}
			}
		}
	}

}