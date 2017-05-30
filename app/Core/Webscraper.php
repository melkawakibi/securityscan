<?php

namespace App\Core;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceWebsite;
use App\Services\ServiceLink;
use App\Model\ClientService;

class Webscraper{

	public function __construct(){

		$this->client = new Client();
		$this->client->setClient(new GuzzleClient());
		$this->links = array();
		$this->formLinks = array();
		$this->formParams = array();
		$this->serviceWebsite = new ServiceWebsite();
		$this->serviceLink = new ServiceLink();
		
	}

	public function setup($url, $credentials){
		$this->url = $url;
		$request = $this->makeRequest($url);
		$cs = new ClientService($request, $this->client);
		$this->processWebsite($url, $this->links($request, $cs), $this->formLinks($request, $cs), $this->paramsPOST($request), $cs);
		$this->nextPages($url);
	}

	public function makeRequest($url){
		return $this->client->request('GET', $url);
	}

	public function links($request, $cs){

		$request->filter('a')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$request->filter('link')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$this->links = $this->filterOutInvalid($this->links, $cs);
		$this->links = $this->filterOutDuplicates($this->links);

		return $this->links;
	}

	public function formLinks($request, $cs){

		$request->filter('form')->each(function ($node) {
		    array_push($this->formLinks, $node->attr('action'));
		});

		$this->formLinks = $this->filterOutInvalid($this->formLinks, $cs);
		$this->formLinks = $this->filterOutDuplicates($this->formLinks);

		return $this->formLinks;
	}

	public function paramsPOST($request){

		$request->filter('input[type=text]')->each(function ($node) {
		    array_push($this->formParams, $node->attr('name'));
		});

		$request->filter('input[type=password]')->each(function ($node) {
		    array_push($this->formParams, $node->attr('name'));
		});

		return $this->formParams;
	}

	public function paramsGET($request){
		
		//TODO

	}

	public function nextPages($url){

		$website = $this->serviceWebsite->findOneByName($url);
		$links = $this->serviceLink->findAllByWebsiteId($website[0]->id);


		foreach ($links as $key => $link) {

			LOG::info("URL: " . $link);

			//create request
			$request = $this->makeRequest($link->url);

			//create model
			$cs = new ClientService($request, $this->client);

			LOG::info("URL: " . $cs->getUri());

			$pageLinks = $this->links($cs->getRequest(), $cs);
			$pageFormLinks = $this->formLinks($cs->getRequest(), $cs);
			$pageParams = $this->paramsPOST($cs->getRequest());

			$this->processWebsite($link->url, $pageLinks, $pageFormLinks, $pageParams, $cs);

		}
	}


	public function filterOutInvalid($links, $client){

		foreach ($links as $key => $link) {


			$scheme = parse_url($link, PHP_URL_SCHEME);

			//check if scheme is empty, if so contunue
			if(empty($scheme)){
			

					//get path
					$path = parse_url($link, PHP_URL_PATH);

					$newlink = '';

					//check which scheme to append
					if(preg_match('/^(https?)/', $client->getScheme())){
	

						$port = (!empty($client->getPort()) ? ':' . $client->getPort() : '');

						if(!empty($path[0])){
							$path  = ( $path[0] === '/' ? substr($path, 1) : $path);
						}

						$newlink = 	$client->getScheme() . '://' . $client->getBaseUri() . $port . '/' . $path;	
	
					}

					//get path extension
					$parsed_url = parse_url($newlink);

					if(!empty($parsed_url['path'])){

						$parts = pathinfo($parsed_url['path']);

						if(!empty($parts['extension'])){

	 						$ext = $parts['extension'];



							//check if correct extension, if not unset else append url to array list
		            		if($ext === 'css' || $ext === 'png' || $ext === 'ico' || $ext === 'svg' || $ext === 'json' || $ext === 'xml') {

		            			unset($links[$key]);
		        			}else{

		        				array_push($links, $newlink);
		        			}
	        			}
        			}

        			//second check if old link still is set, at this point we don't need the old link anymore so unset
        			if(isset($links[$key])){

        				unset($links[$key]);
        			}
			}else{

				//get path extension
				$parsed_url = parse_url($link);

					if(!empty($parsed_url['path'])){
						$parts = pathinfo($parsed_url['path']);

						if(!empty($parts['extension'])){
	 					$ext = $parts['extension'];

						//scheme is not empty check if extension is correct otherwise unset
						if($ext === 'css' || $ext === 'png' || $ext === 'ico' || $ext === 'svg' || $ext === 'json' || $ext === 'xml') {

		            		unset($links[$key]);
		            	}
	            	}
        		}
			}
		}


		foreach ($links as $key => $link) {
			if(!preg_match('/^(https?)/', $link)){
				unset($links[$key]);
			}
		}

		return $links;

	}

	public function filterOutDuplicates($array){
		return array_unique($array);
	}

	public function processWebsite($url, $links, $formLinks, $params, $client){

		//initialize
		$server = $client->getServer();

		LOG::info($url);

		try{

	 		//Check if website already exists
	 		if(!$this->serviceWebsite->numRowByName($url)){

	 			//safe website
				$this->serviceWebsite->create($url, $server);

				//get id website
				$website = $this->serviceWebsite->findOneByName($url);

				if(!empty($client->getHeaders())){
					
					//save headers
					foreach ($client->getHeaders() as $key => $values) {
						foreach ($values as $value) {

							if(strlen($value) < 191){
								$this->serviceWebsite->createHeaders($key, $value, $website[0]->id);
							}
						}
					}
				}

			}else{
				//get id website
				$website = $this->serviceWebsite->findOneByName($url);
			}

			$storedLinks = $this->serviceLink->findAllByWebsiteId($website[0]->id);

			//LOG::info($storedLinks);

			//check if data links already exist for this websits
			if(empty($storedLinks[0])){

				//LOG::info("No storedLinks");

				if(!empty($links)){

					//save links
					foreach ($links as $key => $value) {

						if(strlen($value) < 191){

							$this->serviceLink->create("GET", $value, $website[0]->id);
						}
					}
				}

				if(!empty($formLinks)){

					LOG::info($formLinks);

					//save form links
					foreach ($formLinks as $key => $value) {

						LOG::info('Form links: ' . $value);
						
						 $link = $this->serviceLink->create("POST", $value, $website[0]->id);

						 //save params
						 foreach ($this->formParams as $key => $param) {


						 	$this->serviceLink->createParams($param, $link->id);

						 
						 }
					}
				}
			}

		}catch(Exception $e){
			Log::info($e->getMessage());
		}
	}
	
}