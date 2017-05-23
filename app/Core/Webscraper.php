<?php

namespace App\Core;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceWebsite;
use App\Services\ServiceLink;

class Webscraper{

	private $request;
	private $client;
	private $response;
	private $links;
	private $formLinks;
	private $serviceWebsite;
	private $serviceLink;
	private $url;

	public function __construct(){

		$this->client = new Client();
		$this->client->setClient(new GuzzleClient());
		$this->links = array();
		$this->formLinks = array();
		$this->serviceWebsite = new ServiceWebsite();
		$this->serviceLink = new ServiceLink();
		
	}

	public function setup($url){
		$this->url = $url;
		$this->makeRequest($url);
		$this->processWebsite();
	}

	public function links(){
		$this->request->filter('a')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$this->request->filter('link')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$this->links = $this->filterOutInvalid($this->links);
		$this->links = $this->filterOutDuplicates($this->links);

		return $this->links;
	}

	public function formLinks(){
		$this->request->filter('form')->each(function ($node) {
		    array_push($this->formLinks, $node->attr('action'));
		});

		$this->formLinks = $this->filterOutInvalid($this->formLinks);
		$this->formLinks = $this->filterOutDuplicates($this->formLinks);

		return $this->formLinks;
	}

	public function makeRequest($url){
		$this->request = $this->client->request('GET', $url);
	}

	public function getUri(){
		return $this->request->getUri();
	}

	public function getBaseUri(){
		return parse_url($this->getUri(), PHP_URL_HOST);
	}

	public function getScheme(){
		return parse_url($this->getUri(), PHP_URL_SCHEME);
	}

	public function getPort(){
		return parse_url($this->getUri(), PHP_URL_PORT);
	}

	public function getResonse(){
		return $this->client->getResponse();
	}

	public function getStatus(){
		return $this->getResonse()->getStatus();
	}

	public function getHeaders(){
		return $this->getResonse()->getHeaders();
	}

	public function getServer(){

		foreach ($this->getHeaders() as $key => $value) {

			if($key === 'Server' || $key === 'server'){
				return $value[0];
			}else{
				$server = "No-server-found";
			}
		}

		return $server;
	}

	public function getContent(){
		return $this->getResonse()->getContent();
	}

	public function filterOutInvalid($links){

		foreach ($links as $key => $link) {

			Log::info('--------------links------------------');
			Log::info($link . PHP_EOL);
			Log::info('--------------links------------------');


			$scheme = parse_url($link, PHP_URL_SCHEME);

			//check if scheme is empty, if so contunue
			if(empty($scheme)){
					Log::info('sheme 1');
					Log::info($this->getScheme());

					//get path
					$path = parse_url($link, PHP_URL_PATH);
					Log::info('Path: ' . $path);

					$newlink = '';

					//check which scheme to append
					if(preg_match('/^(https?)/', $this->getScheme())){
						Log::info('sheme 2');

						$port = (!empty($this->getPort()) ? ':' . $this->getPort() : '');
						Log::info($port);

						$path  = ( $path[0] === '/' ? substr($path, 1) : $path);
						Log::info($port);

						$newlink = 	$this->getScheme() . '://' . $this->getBaseUri() . $port . '/' . $path;	
						Log::info($newlink);
					}

					//get path extension
					$parsed_url = parse_url($newlink);

					if(!empty($parsed_url['path'])){

						$parts = pathinfo($parsed_url['path']);

						if(!empty($parts['extension'])){

	 						$ext = $parts['extension'];

							Log::info($ext);

							//check if correct extension, if not unset else append url to array list
		            		if($ext === 'css' || $ext === 'png' || $ext === 'ico' || $ext === 'svg' || $ext === 'json' || $ext === 'xml') {
		            			Log::info('extension 1');
		            			Log::info('unsset 1');
		            			unset($links[$key]);
		        			}else{
		        				Log::info('push');
		        				array_push($links, $newlink);
		        			}
	        			}
        			}

        			//second check if old link still is set, at this point we don't need the old link anymore so unset
        			if(isset($links[$key])){
        				Log::info('unsset 2');
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
							Log::info('extension 2');
							Log::info('unsset 3');
		            		unset($links[$key]);
		            	}
	            	}
        		}
			}
		}

		Log::info($links);

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

	public function processWebsite(){

		//initialize
		$this->server = $this->getServer();
		 
		try{

	 		//Check if website already exists
	 		if(!$this->serviceWebsite->numRowByName($this->url)){

	 			//safe website
				$this->serviceWebsite->create($this->url, $this->server);

				//get id website
				$website = $this->serviceWebsite->findOneByName($this->url);

				if(!empty($this->getHeaders())){
					
					//save headers
					foreach ($this->getHeaders() as $key => $values) {
						foreach ($values as $value) {
							$this->serviceWebsite->createHeaders($key, $value, $website[0]->id);
						}
					}
				}

			}else{
				//get id website
				$website = $this->serviceWebsite->findOneByName($this->url);
			}

			$links = $this->serviceLink->findAllByWebsiteId($website[0]->id);

			//check if data links already exist for this websits
			if(empty($links[0])){

				if(!empty($this->links())){

					//save links
					foreach ($this->links() as $key => $value) {

						$this->serviceLink->create("GET", $value, $website[0]->id);

					}

				}

				if(!empty($this->formLinks())){

					//save form links
					foreach ($this->formLinks() as $key => $value) {
						
						 $this->serviceLink->create("POST", $value, $website[0]->id);

					}
				}
			}

		}catch(Exception $e){
			Log::info($e->getMessage());
		}
	}

	public function printInfoWebsite(){
		 Log::info('url: ' . $this->getUri());
		 Log::info('Base url: ' . $this->getBaseUri());
		 Log::info('status: ' . $this->getStatus());
		 Log::info('server: ' . $this->getServer());
		 Log::info('Date: ' . Carbon::now());
		 Log::info('headers: ' . print_r($this->getHeaders(), TRUE));
		 Log::info('links: ' . print_r($this->links(), TRUE));
		 Log::info('form links: ' . print_r($this->formLinks(), TRUE));
	}
	
}