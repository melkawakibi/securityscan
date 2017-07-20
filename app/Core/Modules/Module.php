<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use App\DB\LinkDB;
use App\DB\WebsiteDB;
use App\DB\ScanDB;

use App\Core\Utils;

abstract class Module
{

	protected $url;
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
		$this->urlArray = array();
		$this->defaultlinks = array();
		$this->properties = array();
	}

	abstract public function start($scan);

	abstract protected function attackGet($link);

	abstract protected function attackPost($link);

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

				$lines = file(public_path() . $payload);

				if($link->methode === 'GET'){
					foreach($lines as $line){

						if(count($params) > 1){

							$urlWithQuery = $this->multiQueryBuilder($baseUrl)->append($param, $line, count($params), $key);

						}

					}
				}

				array_push($this->urlArray, $urlWithQuery);

			}
		}
	}

	protected function multiQueryBuilder($url)
	{	
		$this->url = $url.'?';

		return $this;
	}

	protected function append($str1, $str2 = "", $size = 0, $index = 0)
	{
		if($index < $size){
			$this->url .= sprintf("%s=%s&", $str1, $str2);
		}else{
			$this->url .= sprintf("%s=%s", $str1, $str2);
		}

		return $this->url;
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