<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SQLModule{

	private $client;
	private $baseUrl;
	private $links;
	private $formLinks;


	public function __construct(){
		$this->client = new GuzzleClient();
	}

	public function GET_attack(){

		foreach($this->links as $link){
			if($this->isValid($link)){
				$link = 'https://' . $this->baseUrl . $link;
			}	

			try{
				$res = $this->client->request('GET', $link);
				Log::info('-----------------Response Code -------------------------' . PHP_EOL);
				Log::info('Request url: ' . $link);
				Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
			}catch(RequestException $e){
				Log::info('Request url: ' . $link);
				Log::info('response: ' . $e->getCode() . PHP_EOL);
				Log::info($e->getResponse()->getBody());

			}
		}

	}

	
	public function isValid($link){
		if(strpos($link, $this->baseUrl) === false && substr( $link, 0, 5 ) !== "https" && substr( $link, 0, 4 ) !== "http" 
			&& substr( $link, 0, 3) !== "tel" && substr( $link, 0, 2 ) !== "//"){
			return true;
		}else{
			return false;
		}
	}


}