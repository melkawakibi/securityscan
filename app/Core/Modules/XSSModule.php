<?php

namespace App\Core\Modules;

use App\Core\Modules\Module;

use App\Core\Utils;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;

class XSSModule extends Module
{

	public function __construct($url)
	{
		parent::__construct($url);	
	}

	public function start($scan)
	{
		$website = $this->websiteDB->findOneByUrl($this->url);

		$links = $this->linkDB->findAllByWebsiteId($website[0]->id);

		if(!empty($links)){

			$this->linkList($links, Lang::get('string.payload_xss'));


			echo 'XSS attack'.PHP_EOL.PHP_EOL;
			echo 'Links'.PHP_EOL;
			foreach ($this->uriArray as $key => $value) {
				echo $value.PHP_EOL.PHP_EOL;
			}

			$this->attackGet($links);

		}

		$this->properties['module_name'] = 'xss';

		//These are variable value, I keep them static for now
		$this->properties['risk'] = 'high';
		$this->properties['wasc_id'] = '8';

		$this->scanDB->createScanDetail($scan->id, $scan->scan_key, $this->properties);
	}

	public function attackGet($link)
	{

		foreach ($this->uriArray as $key => $value) {

			//place this before any script you want to calculate time
			$time_start = microtime(true); 
								
			//execute blind sql injections
			$res = $this->client->request('GET', $value);

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			if(strcmp($this->getBaseContent($this->url), $res->getBody())){
				
				echo 'Result: '.PHP_EOL;
				echo 'URI: '.$value.PHP_EOL;

				echo 'Time: '.$execution_time.PHP_EOL;

				$params = Utils::filterGetUrl($value);

				$this->properties['parameter'] = $params[0];

				$this->properties['attack'] = $value;

				$this->properties['execution_time'] = $execution_time;

				if($this->responseAnalyse($res, Lang::get('string.XSS_Attack'))){
					echo 'This webpage is vulnerable for Cross site scripting'.PHP_EOL;
					echo Lang::get('string.XSS').PHP_EOL.PHP_EOL;
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

	public function attackPost($link)
	{

	}

	protected function linkList($links, $payload)
	{

		foreach ($links as $key => $link) {

			$baseUrl = Utils::getBaseUrl($link->url);

			$params = $this->linkDB->findAllByLinkId($link->id);

			foreach ($params as $key => $param) {

				if($link->methode === 'GET'){

					$lines = file(public_path() . $payload);
					
					foreach($lines as $line){
						if($link->id === $param->link_id){
							array_push($this->uriArray, $baseUrl .'?'. $param->params.'='.$line);
						}
					}
				}
			}
		}
	}

	protected function responseAnalyse($res, $str)
	{

		$response = $res->getBody();

		if(strpos($response, $str)){
			return true;
		}else if($response){
			return false;
		}
	}

}