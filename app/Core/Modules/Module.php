<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use App\DB\LinkDB;
use App\DB\WebsiteDB;
use App\DB\ScanDB;

abstract class Module
{

	protected $client;
	protected $baseUrl;
	protected $links;
	protected $formLinks;
	protected $linkDB;
	protected $websiteDB;
	protected $scanDB;

	public function __construct($url)
	{
		$this->url = $url;
		$this->client = new GuzzleClient;
		$this->linkDB = new LinkDB;
		$this->websiteDB = new WebsiteDB;
		$this->scanDB = new ScanDB;	
		$this->uriArray = array();
		$this->defaultlinks = array();
		$this->properties = array();
	}

	abstract public function start($scan);

	abstract public function attackGet($link);

	abstract public function attackPost($link);

	protected function getBaseContent($url)
	{
		$res = $this->client->request('GET', $this->url);
		return $res->getBody();
	}

	protected function linkList($links, $payload)
	{

		foreach ($links as $key => $link) {

			$baseUrl = Utils::getBaseUrl($link->url);

			$params = $this->linkDB->findAllByLinkId($link->id);

			foreach ($params as $key => $param) {

				if($link->methode === 'GET'){

					$lines = file(public_path() . '/resources/payload/sqlblind-injection.txt');
					
					foreach($lines as $line){
						if($link->id === $param->link_id){
							array_push($this->uriArray, $baseUrl.'?'.$param->params.'='.$line);
						}
					}
				}
			}
		}
	}

	protected function responseAnalyse($res, $str)
	{
		$response = $res->getBody();

		if(strpos($response, $str)){
			return true;
		}else if($response){
			return false;
		}
	}
}