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

	public function start()
	{

		$website = $this->websiteDB->findOneByUrl($this->url);

		$scan = $this->scanDB->findLastByScanIdOrderDesc($website[0]->id);

		$links = $this->linkDB->findAllByWebsiteId($website[0]->id);

		if(!empty($links)){

			$this->linkList($links, Lang::get('string.payload_xss'));

			echo 'XSS attack'.PHP_EOL.PHP_EOL;

			$this->attackGet($links, $scan);

		}else{
			echo 'No links to scan'.PHP_EOL;
		}

	}

	protected function attackGet($link, $scan)
	{

		foreach ($this->urlArray as $key => $value) {

			//place this before any script you want to calculate time
			$time_start = microtime(true); 
								
			//execute blind sql injections
			$res = $this->client->request('GET', $value);

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			if (strcmp($this->getBaseContent($this->url), $res->getBody())) {
				
				// echo 'Result: '.PHP_EOL;
				// echo 'URI: '.$value.PHP_EOL;

				// echo 'Time: '.$execution_time.PHP_EOL;

				$params = Utils::filterGetUrl($value);

				$this->properties['parameter'] = $params[0];

				$this->properties['attack'] = $value;

				$this->properties['execution_time'] = $execution_time;

				// Log::info('Time: ' . $execution_time);
				// Log::info('----------------- Response Code -------------------------' . PHP_EOL);
				// Log::info('Request url: ' . $value);
				// Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				// Log::info('----------------- Content -------------------------' . PHP_EOL);
				// Log::info('Content: ' .PHP_EOL. $res->getBody() . PHP_EOL);

				$this->properties['module_name'] = 'xss';

				//These are variable value, I keep them static for now
				$this->properties['risk'] = 'high';
				$this->properties['wasc_id'] = '8';

				if ($this->responseAnalyse($res, Lang::get('string.XSS_Attack'))) {
					echo 'This webpage is vulnerable for Cross site scripting'.PHP_EOL;
					echo Lang::get('string.XSS').PHP_EOL.PHP_EOL;
					
					if(!is_null($scan)){
						$this->scanDB->createScanDetail($scan[0]->id, $this->properties);
					}
				}
			}		
		}
	}

	protected function attackPost($link)
	{

	}

	protected function responseAnalyse($res, $str)
	{

		$response = $res->getBody();

		if (strpos($response, $str)) {
			return true;
		} else if ($response) {
			return false;
		}
	}

}