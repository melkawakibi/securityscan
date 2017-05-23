<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceLink;
use App\Services\ServiceWebsite;

class SQLModule{

	private $client;
	private $baseUrl;
	private $links;
	private $formLinks;
	private $serviceLink;
	private $serviceWebsite;

	public function __construct($url){
		$this->url = $url;
		$this->client = new GuzzleClient;
		$this->serviceLink = new ServiceLink;
		$this->serviceWebsite = new ServiceWebsite;
	}

	public function attackGET(){
		
		$website = $this->serviceWebsite->findOneByName($this->url);

		$links = $this->serviceLink->findAllByWebsiteId($website[0]->id);

		if(!empty($links)){
			foreach ($links as $key => $link) {
				try{
					$res = $this->client->request('GET', $link->url);
					Log::info('-----------------Response Code -------------------------' . PHP_EOL);
					Log::info('Request url: ' . $link->url);
					Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				}catch(RequestException $e){
					Log::info('Request url: ' . $link->url);
					Log::info('response: ' . $e->getCode() . PHP_EOL);
					Log::info($e->getResponse()->getBody() . PHP_EOL);

				}
			}
		}
	}

}