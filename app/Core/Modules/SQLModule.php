<?php

namespace App\Core\Modules;

use App\Core\Modules\Module;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\ScanDetailService as ScanDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;
use Curl;
use \stdClass as Object;
use App\Core\Utils;

class SQLMOdule extends Module
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

			$this->buildGETURI($links, Lang::get('string.payload_sql'));

			$this->buildPostFormParams($links, Lang::get('string.payload_sql'));

			echo 'SQLI attack'.PHP_EOL.PHP_EOL;

			//$this->attackGet($scan);

			//$this->attackPost($scan);

		}else{
			echo 'No links to scan'.PHP_EOL;
		}
	}

	protected function attackGet($scan)
	{

		foreach ($this->queryArray as $key => $value) {

			$time_start = microtime(true);
			
			$res = 'default';

			if (filter_var($value, FILTER_VALIDATE_URL) !== false){
				
				$res = $this->client->request('GET', $value);
			}

			$time_end = microtime(true);

			$duration = $time_end-$time_start;
			$hours = (int)($duration/60/60);
			$minutes = (int)($duration/60)-$hours*60;
			$seconds = (int)$duration-$hours*60*60-$minutes*60;


			if($res !== 'default'){
				if(strcmp($this->getBaseContent($this->url), $res->getBody())){

					$params = Utils::filterGetUrl($value);

					$this->properties['parameter'] = $params[0];
					$this->properties['execution_time'] = $seconds;
					$this->properties['module_name'] = Lang::get('string.SQL.module');
					$this->properties['risk'] = Lang::get('string.SQL.risk');
					$this->properties['wasc_id'] = Lang::get('string.SQL.wasc_id');	
					$this->properties['method'] = 'GET';				

					$sql_array = explode("=", $value);
					$sql_url = explode("?", $sql_array[0]);
					$sql_attack = urldecode($sql_array[1]);

					$this->properties['target'] =  $sql_url[0];
					$this->properties['attack'] = $sql_attack;

					foreach (Lang::get('error_sql') as $key => $value) {

						$content = $res->getBody();
						
						if($this->find_sql($content, $value)){

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

				$response = '';

					if(is_array($values)){

						$attack = $this->getAttack($values);

						$values[$submitParam] = $submitValue;

						$time_start = microtime(true);

						$path = storage_path('logs');

						$response = Curl::to($url)
						        ->withData( $values )
						        ->enableDebug($path . "/logDebug.txt")
						        ->returnResponseObject()
						        ->post();

						$time_end = microtime(true);

						$duration = $time_end-$time_start;
						$hours = (int)($duration/60/60);
						$minutes = (int)($duration/60)-$hours*60;
						$seconds = (int)$duration-$hours*60*60-$minutes*60;

					if($response !== 'default'){
						if(strcmp($this->getBaseContent($this->url), $response->content)){

							$this->properties['parameter'] = 'form-params';
							$this->properties['execution_time'] = $seconds;
							$this->properties['module_name'] = Lang::get('string.SQL.module');
							$this->properties['risk'] = Lang::get('string.SQL.risk');
							$this->properties['wasc_id'] = Lang::get('string.SQL.wasc_id');
							$this->properties['method'] = 'POST';
							$this->properties['target'] =  $url;
							$this->properties['attack'] = $attack;

							foreach (Lang::get('error_sql') as $key => $value) {

								$content = $response->content; 					
								
								if($this->find_sql($content, $value)){

									$this->properties['error'] = 'error';

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
		}
	}

	protected function getAttack($values)
	{
		foreach ($values as $key => $value) {
			
			return $value;

		}
	}	

	protected function find_sql($content, $str)
	{

		if(strpos($content, $str)){
			return true;
		}else{
			return false;
		}
	}

}