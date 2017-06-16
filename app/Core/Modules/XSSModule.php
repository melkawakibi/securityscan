<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use App\DB\LinkDB;
use App\DB\WebsiteDB;
use App\DB\ScanDB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;

class XSSModule{

	private $client;
	private $baseUrl;
	private $links;
	private $formLinks;
	private $linkDB;
	private $websiteDB;

	public function __construct($url){
		$this->url = $url;
		$this->client = new GuzzleClient;
		$this->linkDB = new LinkDB;
		$this->websiteDB = new WebsiteDB;
		$this->scanDB = new ScanDB;	
		$this->uriArray = array();
		$this->defaultlinks = array();
		$this->properties = array();
	}

	public function attack($scan){

		$website = $this->websiteDB->findOneByUrl($this->url);

		$links = $this->linkDB->findAllByWebsiteId($website[0]->id);

		if(!empty($links)){

			$this->linkList($links);

			$this->linkAttack($links);

		}else{

			echo 'Default scan started';

			//default attack	
			$this->defaultAttack();
		}

		$this->properties['module_name'] = 'xss';

		//These are variable value, I keep them static for now
		$this->properties['risk'] = 'high';
		$this->properties['wasc_id'] = '8';

		$this->scanDB->createScanDetail($scan->id, $scan->scan_key, $this->properties);
	}

	private function linkList($links){

			foreach ($links as $key => $link) {

			$params = $this->linkDB->findAllByLinkId($link->id);

			foreach ($params as $key => $param) {

				if($link->methode === 'GET'){

					$lines = file(public_path() . '/resources/payload/xss.txt');
					
					foreach($lines as $line){
						if($link->id === $param->link_id){
							array_push($this->uriArray, $link->url.'?'.$param->params.'='.$line);
						}
					}
				}
			}
		}
	}

	private function responseAnalyse($res){

		$response = $res->getBody();

		if(strpos($response, '<script>alert(1);</script>')){
			return true;
		}else if($response){
			return false;
		}
	}

	private function getBaseContent($url){

		$res = $this->client->request('GET', $this->url);
		return $res->getBody();
	}

	public function defaultAttack(){

		$lines = file(public_path() . '/resources/payload/xss-default.txt');
		
		foreach($lines as $line){

			array_push($this->defaultlinks, $this->url.'?'.$line);

		}

		echo 'default XSS attack'.PHP_EOL;
		foreach ($this->defaultlinks as $key => $value) {

			//place this before any script you want to calculate time
			$time_start = microtime(true); 
								
			//execute blind sql injections
			$res = $this->client->request('GET', $value);

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			if(strcmp($this->getBaseContent($this->url), $res->getBody())){
			
				echo 'URI: '.$value.PHP_EOL;

				echo 'Time: '.$execution_time.PHP_EOL;

				$params = $this->filterGetUrl($value);

				$this->properties['parameter'] = $params[0];

				$this->properties['attack'] = $value;
				$this->properties['execution_time'] = $execution_time;

				if($this->responseAnalyse($res)){
					echo '	'.PHP_EOL;
					echo Lang::get('description.XSS').PHP_EOL.PHP_EOL;
				}

				Log::info('Time: ' . $execution_time);
				Log::info('----------------- Response Code -------------------------' . PHP_EOL);
				Log::info('Request url: ' . $value);
				Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				Log::info('----------------- Content -------------------------' . PHP_EOL);
				Log::info('Content: ' .PHP_EOL. $res->getBody() . PHP_EOL);
			}

		}

	}

	public function linkAttack($links){

		echo 'XSS attack'.PHP_EOL;
		foreach ($this->uriArray as $key => $value) {

			//place this before any script you want to calculate time
			$time_start = microtime(true); 
								
			//execute blind sql injections
			$res = $this->client->request('GET', $value);

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			if(strcmp($this->getBaseContent($this->url), $res->getBody())){
			
				echo 'URI: '.$value.PHP_EOL;

				echo 'Time: '.$execution_time.PHP_EOL;

				$params = $this->filterGetUrl($value);

				$this->properties['parameter'] = $params[0];

				$this->properties['attack'] = $value;

				$this->properties['execution_time'] = $execution_time;

				if($this->responseAnalyse($res)){
					echo 'This webpage is vulnerable for Cross site scripting'.PHP_EOL;
					echo Lang::get('description.XSS').PHP_EOL.PHP_EOL;
				}

				Log::info('Time: ' . $execution_time);
				Log::info('----------------- Response Code -------------------------' . PHP_EOL);
				Log::info('Request url: ' . $value);
				Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				Log::info('----------------- Content -------------------------' . PHP_EOL);
				Log::info('Content: ' .PHP_EOL. $res->getBody() . PHP_EOL);
			}		
		}
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
}