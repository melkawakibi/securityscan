<?php

namespace App\Core\Modules;

use App\Core\Modules\Module;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\ScanDetailService as ScanDetail;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Curl;
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

			$this->buildGETURI($links, Lang::get('string.payload_blind_sql'));

			$this->buildPostFormParams($links, Lang::get('string.payload_blind_sql'));

			echo 'Blind SQLI attack'.PHP_EOL.PHP_EOL;

			//$this->attackPost($scan);

			//$this->attackGet($scan);

		}else{
			echo 'No links to scan'.PHP_EOL;
		}
	}

	protected function attackGet($scan)
	{

		foreach ($this->queryArray as $key => $link) {

			$time_start = microtime(true);

			if (filter_var($link, FILTER_VALIDATE_URL) !== false){
				
				$this->client->request('GET', $link);
			}

			$time_end = microtime(true);

			$duration = $time_end-$time_start;
			$hours = (int)($duration/60/60);
			$minutes = (int)($duration/60)-$hours*60;
			$seconds = (int)$duration-$hours*60*60-$minutes*60;

			$params = Utils::filterGetUrl($link);

			$this->properties['parameter'] = $params[0];
			$this->properties['execution_time'] = $seconds;
			$this->properties['module_name'] = Lang::get('string.BlindSQL.module');
			$this->properties['risk'] = Lang::get('string.BlindSQL.risk');
			$this->properties['wasc_id'] = Lang::get('string.BlindSQL.wasc_id');
			$this->properties['method'] = 'GET';

			$sql_array = explode("=", $link);
			$sql_url = explode("?", $sql_array[0]);
			$sql_attack = urldecode($sql_array[1]);

			$this->properties['target'] =  $sql_url[0];
			$this->properties['attack'] = $sql_attack;

			$this->properties['error'] = $link;

			if($seconds >= $this->timeout){
				if(!is_null($scan)){
					$scanDetail = new Object;
					$scanDetail->scan_id = $scan[0]->id;
					$scanDetail->properties = $this->properties;
					ScanDetail::store($scanDetail);
				}
			}	
		}
	}

	protected function attackPost($scan)
	{

		foreach ($this->formArray as $key => $formArray) {
			
			$id = $formArray['id'];
			$submitParam = $formArray['param'];
			$submitValue = $formArray['value'];

			$link = Link::findOneById($id);

			$url = $link->first()->url;

			foreach ($formArray as $key => $values) {

				$res = '';

				if(is_array($values)){

					$values[$submitParam] = $submitValue;

					$time_start = microtime(true);

					$path = storage_path('logs');

					$response = Curl::to($url)
					        ->withData( $values )
					        ->enableDebug($path . "/logDebug.txt")
					        ->returnResponseObject()
					        ->post();

					$time_end = microtime(true);

				    $content = $response->content; 

					Log::info('----------------- Response Code -------------------------' . PHP_EOL);
					Log::info('Request url: ' . $url);
					Log::info('response: ' . $response->status . PHP_EOL);
					Log::info('----------------- Content -------------------------' . PHP_EOL);
					Log::info('Content: ' . PHP_EOL . $response->content . PHP_EOL);				

					$duration = $time_end-$time_start;
					$hours = (int)($duration/60/60);
					$minutes = (int)($duration/60)-$hours*60;
					$seconds = (int)$duration-$hours*60*60-$minutes*60;

					$this->properties['parameter'] = 'form-params';
					$this->properties['execution_time'] = $seconds;
					$this->properties['module_name'] = Lang::get('string.BlindSQL.module');
					$this->properties['risk'] = Lang::get('string.BlindSQL.risk');
					$this->properties['wasc_id'] = Lang::get('string.BlindSQL.wasc_id');
					$this->properties['method'] = 'POST';
					$this->properties['target'] =  $url;
					$this->properties['attack'] = 'attack';

					$this->properties['error'] = 'error';

					if($seconds >= $this->timeout){
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

}