<?php

namespace App\Core\Modules;

use App\Core\Modules\Module;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\ScanDetailService as ScanDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use \stdClass as Object;
use App\Core\Utils;

class BlindSQLModule extends Module
{

	public function __construct($url)
	{
		parent::__construct($url);	
	}

	public function start()
	{

		$website = Website::findOneByUrl($this->url);

		$scan = Scan::findLastByScanIdOrderDesc($website[0]->id);

		$links = Link::findAllByWebsiteId($website[0]->id);

		if(!empty($links)){

			$this->linkList($links, Lang::get('string.payload_sql'));

			echo 'SQLI attack'.PHP_EOL.PHP_EOL;

			$this->attackGet($scan);

		}else{
			echo 'No links to scan'.PHP_EOL;
		}
	}

	protected function attackGet($scan)
	{

		foreach ($this->urlArray as $key => $value) {

			//place this before any script you want to calculate time
			$time_start = microtime(true);
								
			//execute blind sql injections
			$res = $this->client->request('GET', $value);

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			if(strcmp($this->getBaseContent($this->url), $res->getBody())){
				
				// echo 'Result: '.PHP_EOL;
				//echo 'URI: '.$value.PHP_EOL;

				// echo 'Time: '.$execution_time.PHP_EOL;

				$params = Utils::filterGetUrl($value);

				$this->properties['parameter'] = $params[0];

				$this->properties['attack'] = $value;

				$this->properties['execution_time'] = $execution_time;

				Log::info('Time: ' . $execution_time);
				Log::info('----------------- Response Code -------------------------' . PHP_EOL);
				Log::info('Request url: ' . $value);
				Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				Log::info('----------------- Content -------------------------' . PHP_EOL);
				Log::info('Content: ' .PHP_EOL. $res->getBody() . PHP_EOL);

				$this->properties['module_name'] = 'sql';

				//These are variable value, I keep them static for now
				$this->properties['risk'] = 'high';
				$this->properties['wasc_id'] = '19';

				foreach (Lang::get('error_sql') as $key => $value) {
					
					if($this->responseAnalyse($res, $value)){
						echo 'This webpage is vulnerable for SQL injections'.PHP_EOL;
						$this->properties['error'] = $value;

						if(!is_null($scan)){
							$scanDetail = new Object;
							$scanDetail->scan_id = $scan[0]->id;
							$scanDetail->properties = $this->properties;
							ScanDetail::store($scanDetail);
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