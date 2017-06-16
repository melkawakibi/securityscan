<?php

namespace App\Core;

use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use App\Model\Field;
use App\DB\WebsiteDB;
use App\DB\LinkDB;
use App\Model\Client;
use App\Services\LoginService;

class Crawler{

	private $url;

	private $links;

	private $formLinks;	

	public function __construct(){

		$this->client = new GoutteClient;
		$this->client->setClient(new GuzzleClient());
		$this->links = array();
		$this->formLinks = array();
		$this->serviceWebsite = new WebsiteDB;
		$this->serviceLink = new LinkDB;
		
	}

	public function setup($url){
		$this->url = $url;
		$this->request = $this->makeRequest($url);
		$this->cs = new Client($this->request, $this->client);
	}

	public function makeRequest($url){
		return $this->client->request('GET', $url);
	}

	public function crawl(){

		$this->links();
		$this->formLinks();
		$this->processWebsite();

	}

	public function crawlWithLogin($credentials){
		if(!empty($credentials)){
			
			//Get all the uri's
			$this->links();

			$this->serviceLogin = new LoginService($this->request, $this->client, $credentials, $this->links);
			$isSucces = $this->serviceLogin->login($this->request);

			if($isSucces){
				echo 'login succesfull' . PHP_EOL;
				$this->links();
				$this->formLinks();
				$this->paramsPOST();
				$this->paramsGET();
				$this->processWebsite();
			}else{
				echo 'login unsuccesfull' . PHP_EOL;
			}

		}else{

			echo 'No login' . PHP_EOL;

			$this->links();
			$this->formLinks();
			$this->paramsPOST();
			$this->paramsGET();
			$this->processWebsite();

		}

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

	}

	public function formLinks(){

		$this->request->filter('form')->each(function ($node) {
		    array_push($this->formLinks, $node->attr('action'));
		});

		$this->formLinks = $this->filterOutInvalid($this->formLinks);
		$this->formLinks = $this->filterOutDuplicates($this->formLinks);

	}


	private function filterGetUrl($url){

		$params = array();

		if(strpos($url, "?") !== false){
			$queryLine = explode("?", $url);
			
			if(strpos($queryLine[1], "&") !== false){
				$queries = explode("&", $queryLine[1]);

				foreach ($queries as $key => $query) {
					
					if(strpos($query, "=") !== false){
						$param = explode("=", $query);
						array_push($params, $param[0]);
					}	
				}
			}else{

				if(strpos($queryLine[1], "=") !== false){
					$param = explode("=", $queryLine[1]);
					array_push($params, $param[0]);
				}
			}
		}

		return $params;
	}

	private function filterOutInvalid($links){

		foreach ($links as $key => $link) {


			$scheme = parse_url($link, PHP_URL_SCHEME);

			//check if scheme is empty, if so contunue
			if(empty($scheme)){
			

					//get path
					$path = parse_url($link, PHP_URL_PATH);

					$newlink = '';

					//check which scheme to append
					if(preg_match('/^(https?)/', $this->cs->getScheme())){
	

						$port = (!empty($this->cs->getPort()) ? ':' . $this->cs->getPort() : '');

						if(!empty($path[0])){
							$path  = ( $path[0] === '/' ? substr($path, 1) : $path);
						}

						$newlink = 	$this->cs->getScheme() . '://' . $this->cs->getBaseUri() . $port . '/' . $path;	
	
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

	private function filterOutDuplicates($links){
		return array_unique($links);
	}

	private function processWebsite(){

		//initialize
		$server = $this->cs->getServer();

		try{

	 		//Check if website already exists
	 		if(!$this->serviceWebsite->numRowByUrl($this->url)){

	 				//save website
					$this->serviceWebsite->create($this->url, $server);

					//get id website
					$website = $this->serviceWebsite->findOneByUrl($this->url);

					if(!empty($this->cs->getHeaders())){
					
						//save headers
						foreach ($this->cs->getHeaders() as $key => $values) {
							foreach ($values as $value) {

								if(strlen($value) < 191){
									$this->serviceWebsite->createHeaders($key, $value, $website[0]->id);
								}
							}
						}
					}

					$this->processLinks($website);

			}else{

				$website = $this->serviceWebsite->findOneByUrl($this->url);

				$this->processLinks($website);
			}

		}catch(Exception $e){
			Log::info($e->getMessage());
		}
	}

	public function mergeAndFilter($array1, $array2){

		$array = array_merge($array1, $array2);

		$array = array_unique($array);

		return $array;
		
	}

	public function processLinks($website){

		$storedLinks = $this->serviceLink->findAllByWebsiteId($website[0]->id);

		$linkArray = array();

		foreach ($storedLinks as $key => $value) {
			array_push($linkArray, $value->url);
		}

		$this->links = $this->mergeAndFilter($linkArray, $this->links);

		$linkAndParams = $this->links;

		if(!empty($this->links)){

			foreach ($this->links as $key => $value) {
				
				if(strpos($value, "?") !== false){
					
					$url = explode("?", $value);

					$this->links[$key] = $url[0];
				}
			}

			$this->links = array_unique($this->links);

			//save links
			foreach ($this->links as $key => $value) {
				
				if(strlen($value) < 191){

					if(!$this->serviceLink->numRowByUrl($value)){

						$link = $this->serviceLink->create("GET", $value, $website[0]->id);

						if(!empty($linkAndParams)){

							foreach ($linkAndParams as $key => $value) {
								
								$params = $this->filterGetUrl($value);

								foreach ($params as $key => $param) {
									if(!$this->serviceLink->numRowByName($param)){
										$this->serviceLink->createParams($param, $link->id);
									}
								}
							}
						}
					}
				}
			}
		}

		if(!empty($this->formLinks)){

			//save form links
			foreach ($this->formLinks as $key => $value) {

					if(!$this->serviceLink->numRowByUrl($value)){

						$link = $this->serviceLink->create("POST", $value, $website[0]->id);


						$forms = $this->getForms();

						if(!empty($forms)){

							foreach ($forms as $form) {

								$fields = $form->all();

									if(!empty($fields)){

									if($form->getUri() === $link->url){

										foreach ($fields as $field) {

											$fieldObj = new Field($field);

											$type = $fieldObj->getType();

											if($type === 'text' || $type=== 'password'){
												$this->serviceLink->createParams($field->getName(), $link->id);	
											}
										}
									}
								}
							}
						}
					}

				}
			}
		}

		public function getForms(){

			$this->submits = array();
			$this->forms = array();

			$this->request->filter('input[type=submit]')->each(function($node){

				array_push($this->submits, $node->attr('value'));

			});

			foreach ($this->submits as $key => $value) {
				//echo $value.PHP_EOL;
				$this->forms[] = $this->request->selectButton($value)->form();
			}

			return $this->forms;
		}
	}