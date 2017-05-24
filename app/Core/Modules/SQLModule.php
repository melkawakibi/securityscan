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

		echo public_path() . PHP_EOL;

		if(!empty($links)){
			foreach ($links as $key => $link) {

				$methode = $links->methode;

				if($methode === 'GET'){

					try{

						//print response information of the links url, status
						$res = $this->client->request($methode, $link->url);
						Log::info('-----------------Response Code -------------------------' . PHP_EOL);
						Log::info('Request url: ' . $link->url);
						Log::info('response: ' . $res->getStatusCode() . PHP_EOL);

						if($res->getStatusCode() === 200){

							$lines = file(public_path() . '/resources/payload/sqlblind-injection.txt');

							try{
								//loop through all links
								foreach($lines as $line)
								{	
									$request_url = $link->url . '?test=' . $line;
									
									//place this before any script you want to calculate time
									$time_start = microtime(true); 
									
									//execute blind sql injections
								    $res = $this->client->request($methode, $request_url);

								    $time_end = microtime(true);

								    //dividing with 60 will give the execution time in minutes other wise seconds
									$execution_time = ($time_end - $time_start)/60;

									Log::info('Time: ' . $execution_time);
								    Log::info('url: ' . $request_url);
								    Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
								}
							}catch(RequestException $e){
								Log::info('url for blind sql: ' . $link->url);
								Log::info('response: ' . $e->getCode() . PHP_EOL);
								Log::info($e->getResponse()->getBody() . PHP_EOL);		
							}

						}

					}catch(RequestException $e){
						Log::info('Request url: ' . $link->url);
						Log::info('response: ' . $e->getCode() . PHP_EOL);
						Log::info($e->getResponse()->getBody() . PHP_EOL);
					}

				}else{

					try{
						
						//print response information of the links url, status
						$res = $this->client->request($methode, $link->url);

						Log::info('-----------------Response Code -------------------------' . PHP_EOL);
						Log::info('Request url: ' . $link->url);
						Log::info('response: ' . $res->getStatusCode() . PHP_EOL);

						if($res->getStatusCode() === 200){

							$lines = file(public_path() . '/resources/payload/sqlblind-injection.txt');

							try{
								//loop through all links
								foreach($lines as $line)
								{	
									$request_url = $link->url . '?test=' . $line;
									
									//place this before any script you want to calculate time
									$time_start = microtime(true);
									
									//execute blind sql injections
								    $res = $this->client->request($methode, $request_url, [
									    'form_params' => [
									 ]]);

								    $time_end = microtime(true);

								    //dividing with 60 will give the execution time in minutes other wise seconds
									$execution_time = ($time_end - $time_start)/60;

									Log::info('Time: ' . $execution_time);
								    Log::info('url: ' . $request_url);
								    Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
								}
							}catch(RequestException $e){
								Log::info('url for blind sql: ' . $link->url);
								Log::info('response: ' . $e->getCode() . PHP_EOL);
								Log::info($e->getResponse()->getBody() . PHP_EOL);		
							}

						}
						
					}catch(RequestException $e){
						Log::info('Request url: ' . $link->url);
						Log::info('response: ' . $e->getCode() . PHP_EOL);
						Log::info($e->getResponse()->getBody() . PHP_EOL);

					}
				}
			}
		}
	}

}