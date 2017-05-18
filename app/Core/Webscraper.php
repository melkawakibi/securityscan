<?php

namespace App\Core;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;

class Webscraper{

	private $request;
	private $client;
	private $response;
	private $links;
	private $formLinks;

	public function __construct(){
		$this->client = new Client();
		$this->client->setClient(new GuzzleClient());
		$this->links = array();
		$this->formLinks = array();
	}

	public function links(){
		$this->request->filter('a')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$this->request->filter('link')->each(function ($node) {
		    array_push($this->links, $node->attr('href'));
		});

		$this->links = $this->filterOutInvalid($this->links);
		//$this->links = $this->filterOutDuplicates($this->links)

		return $this->links;
	}

	public function formLinks(){
		$this->request->filter('form')->each(function ($node) {
		    array_push($this->formLinks, $node->attr('action'));
		});

		return $this->formLinks;
	}

	public function makeRequest($url){
		Log::info('url: ' . $url);
		$this->request = $this->client->request('GET', $url);
	}

	public function getUri(){
		return $this->request->getUri();
	}

	public function getBaseUri(){
		return parse_url($this->getUri(), PHP_URL_HOST);
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

	public function filterOutInvalid($array){

		foreach ($array as $key => $link) {

			if(!preg_match("~^(?:f|ht)tps?://~i", $link)){
            	unset($array[$key]);
        	}
		}

		return $array;
	}

	public function filterOutDuplicates($array){
		//TODO 
		// if(!preg_match("[https://]", $link)){
  		//           unset($array[$key]);
  		//       }else if(!preg_match("[http://]", $link)){
  		//           unset($array[$key]);
  		//       }
		// if (substr( $link, 0, 1) === '#' || substr( $link, 0, 3) === 'tel' || substr( $link, 0, 2 ) === "//"  || substr( $link, 0, 1 ) === "/" || substr( $link, 0, 1 ) === "") {
 		//    unset($array[$key]);
		// 	}
	}	
}