<?php

namespace App;

use App\Core\Webscraper as Scraper;
use App\Core\Modules\SQLModule as SQL;
use Illuminate\Support\Facades\Log;
use App\Model\Website;
use App\Services\ServiceWebsite;
use App\Services\ServiceLink;

class Main{

	private $scraper;
	private $url;
	private $server;
	private $serviceWebsite;
	private $serviceLink;

	public function __construct($url){
		 

		 $this->serviceWebsite = new ServiceWebsite();
		 $this->serviceLink = new ServiceLink();

		 //initialize scraper
		 $this->scraper = new Scraper();
		 $this->scraper->makeRequest($url);

		 $this->setup();
		 //$this->scan();
		 //perpare scan
		 	//gather data
		 	//choose modules
		 //scan
	}

	public function setup(){

		$this->printInfoWebsite();

		//process website data, base info, headers. links
		$this->processWebsite();
		
	}

	public function processWebsite(){

		//initialize
		$this->url =  $this->scraper->getBaseUri();
		$this->server = $this->scraper->getServer();
		 
		try{
	 		//Check if website already exists
	 		if(!$this->serviceWebsite->numRowByName($this->url)){

	 			//safe website
				$this->serviceWebsite->create($this->url, $this->server);
				
				//get id website
				$website = $this->serviceWebsite->findOneByName($this->url);

				if(!empty($this->scraper->getHeaders())){
					//save links
					foreach ($this->scraper->getHeaders() as $key => $values) {
						Log::info('Key: ' . $key);
						foreach ($values as $value) {
							$this->serviceWebsite->createHeaders($key, $value, $website[0]->id);
						}
					}
				}

				if(!empty($this->scraper->links())){
					//save links
					foreach ($this->scraper->links() as $key => $value) {

						$this->serviceLink->create("GET", $value, $website[0]->id);

					}
				}

				if(!empty($this->scraper->formLinks())){
					//save form links
					foreach ($this->scraper->formLinks() as $key => $value) {
						
						 $this->serviceLink->create("POST", $value, $website[0]->id);

					}
				}

			}else{
				//Do nothing
			}

		}catch(Exception $e){
			Log::info($e->getMessage());
		}
	}

	public function scan(){
		$Sql = new SQL( $, );
		Log::info('-----------------GET ATTACK-------------------------' . PHP_EOL);
		$Sql->GET_attack();
	}

	public function printInfoWebsite(){
		 //Log::info('url: ' . $this->scraper->getUri());
		 // Log::info('Base url: ' . $this->scraper->getBaseUri());
		 // Log::info('status: ' . $this->scraper->getStatus());
		 // Log::info('server: ' . $this->scraper->getServer());
		 // Log::info('Date: ' . Carbon::now());
		 // Log::info('headers: ' . print_r($this->scraper->getHeaders(), TRUE));
		 // Log::info('links: ' . print_r($this->scraper->links(), TRUE));
		 // Log::info('form links: ' . print_r($this->scraper->formLinks(), TRUE));
	}


}