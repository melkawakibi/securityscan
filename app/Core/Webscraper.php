<?php

namespace App\Core;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceWebsite;
use App\Services\ServiceLink;
use App\Services\LoginService;
use App\Model\ClientService;

class Webscraper{

	public function __construct(){

		$this->client = new Client;
		$this->client->setClient(new GuzzleClient());
		$this->links = array();
		$this->formLinks = array();
		$this->paramsGET = array();
		$this->paramsPOST = array();
		$this->serviceWebsite = new ServiceWebsite;
		$this->serviceLink = new ServiceLink;
		
	}

	public function setup($url, $credentials){

		$this->url = $url;
		$request = $this->makeRequest($url);
		$this->cs = new ClientService($request, $this->client);
		$this->links($request, $this->cs);
		$this->formLinks($request, $this->cs);
		$this->paramsPOST($request);
		$this->paramsGET($request);
		$this->processWebsite($url, $this->cs);

		$this->serviceLogin = new LoginService($request, $this->client, $credentials, $this->links);
		$this->serviceLogin->login($request);
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

		$this->filterOutInvalid($cs);
		$this->filterOutDuplicates();

	}

	public function formLinks($request, $cs){

		$request->filter('form')->each(function ($node) {
		    array_push($this->formLinks, $node->attr('action'));
		});

		$this->filterOutInvalid($cs);
		$this->filterOutDuplicates();

	}

	public function paramsGET($request){
		
		$request->filter('a')->each(function ($node) {
		    array_push($this->paramsGET, $node->attr('href'));
		});

		if(!empty($this->paramsGET)){
			$this->filterGetUrl();
		}
	}

	public function paramsPOST($request){

		$request->filter('input[type=text]')->each(function ($node) {
		    array_push($this->paramsPOST, $node->attr('name'));
		});

		$request->filter('input[type=password]')->each(function ($node) {
		    array_push($this->paramsPOST, $node->attr('name'));
		});

	}

	private function filterGetUrl(){

		$queries = array();

		//Get all key value pairs
		foreach ($this->paramsGET  as $key => $value) {
			if(strpos($value, "?") !== false){
				$query = explode("?", $value);
				array_push($queries, $query[1]);
			}
		}

		$this->paramsGET = array();

		//get all params
		foreach ($queries as $key => $value) {
			if(strpos($value, "=") !== false){
				$param = explode("=", $value);
				array_push($this->paramsGET, $param[0]);
			}
		}

	}


	private function mergeLinks(){
		
		$array = array();
		
		foreach ($this->formLinks as $value) {
			array_push($array, $value);
		}

		foreach ($this->links as $value) {
			array_push($array, $value);
		}

		return $array;
	}


	// private function searchLinks($url){

	// 	$website = $this->serviceWebsite->findOneByName($url);
	// 	$links = $this->serviceLink->findAllByWebsiteId($website[0]->id);

	// 	foreach ($links as $key => $link) {

	// 		LOG::info("URL: " . $link);

	// 		//create request
	// 		$request = $this->makeRequest($link->url);

	// 		//create model
	// 		$cs = new ClientService($request, $this->client);

	// 		LOG::info("URL: " . $cs->getUri());

	// 		$pageLinks = $this->links($cs->getRequest(), $cs);
	// 		$pageFormLinks = $this->formLinks($cs->getRequest(), $cs);
	// 		$pageParams = $this->paramsPOST($cs->getRequest());

	// 		$this->processWebsite($link->url, $pageLinks, $pageFormLinks, $pageParams, $cs);

	// 	}
	// }


	private function filterOutInvalid($cs){

		foreach ($this->links as $key => $link) {


			$scheme = parse_url($link, PHP_URL_SCHEME);

			//check if scheme is empty, if so contunue
			if(empty($scheme)){
			

					//get path
					$path = parse_url($link, PHP_URL_PATH);

					$newlink = '';

					//check which scheme to append
					if(preg_match('/^(https?)/', $cs->getScheme())){
	

						$port = (!empty($cs->getPort()) ? ':' . $cs->getPort() : '');

						if(!empty($path[0])){
							$path  = ( $path[0] === '/' ? substr($path, 1) : $path);
						}

						$newlink = 	$cs->getScheme() . '://' . $cs->getBaseUri() . $port . '/' . $path;	
	
					}

					//get path extension
					$parsed_url = parse_url($newlink);

					if(!empty($parsed_url['path'])){

						$parts = pathinfo($parsed_url['path']);

						if(!empty($parts['extension'])){

	 						$ext = $parts['extension'];



							//check if correct extension, if not unset else append url to array list
		            		if($ext === 'css' || $ext === 'png' || $ext === 'ico' || $ext === 'svg' || $ext === 'json' || $ext === 'xml') {

		            			unset($this->links[$key]);
		        			}else{

		        				array_push($this->links, $newlink);
		        			}
	        			}
        			}

        			//second check if old link still is set, at this point we don't need the old link anymore so unset
        			if(isset($this->links[$key])){

        				unset($this->links[$key]);
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

		            		unset($this->links[$key]);
		            	}
	            	}
        		}
			}
		}

		foreach ($this->links as $key => $link) {
			if(!preg_match('/^(https?)/', $link)){
				unset($this->links[$key]);
			}
		}

	}

	private function filterOutDuplicates(){
		return array_unique($this->links);
	}

	private function processWebsite($url, $cs){

		//initialize
		$server = $cs->getServer();

		try{

	 		//Check if website already exists
	 		if(!$this->serviceWebsite->numRowByName($url)){

	 				//save website
					$this->serviceWebsite->create($url, $server);

					//get id website
					$website = $this->serviceWebsite->findOneByName($url);

					if(!empty($cs->getHeaders())){
					
						//save headers
						foreach ($cs->getHeaders() as $key => $values) {
							foreach ($values as $value) {

								if(strlen($value) < 191){
									$this->serviceWebsite->createHeaders($key, $value, $website[0]->id);
								}
							}
						}
					}

					$this->processLinks($website);

			}else{

				$website = $this->serviceWebsite->findOneByName($url);

				$this->processLinks($website);
			}

		}catch(Exception $e){
			Log::info($e->getMessage());
		}
	}

	public function processLinks($website){

		//TODO array_diff or array_unique merge array
		$storedLinks = $this->serviceLink->findAllByWebsiteId($website[0]->id);

		if(empty($storedLinks[0])){

			if(!empty($this->links)){

				//save links
				foreach ($this->links as $key => $value) {
					
					if(strlen($value) < 191){

						$link = $this->serviceLink->create("GET", $value, $website[0]->id);

						if(!empty($this->paramsGET)){

							//save params
							 foreach ($this->paramsGET as $key => $param) {

							 	$this->serviceLink->createParams($param, $link->id);

							 }
						}
					}
				}
			}

			if(!empty($this->formLinks)){

				//save form links
				foreach ($this->formLinks as $key => $value) {

					//LOG::info('Form links: ' . $value);
					
					 $link = $this->serviceLink->create("POST", $value, $website[0]->id);

					 if(!empty($this->paramsPOST)){

						 //save params
						 foreach ($this->paramsPOST as $key => $param) {

						 	$this->serviceLink->createParams($param, $link->id);

						 }
					}
				}
			}
		}
	}
	
}