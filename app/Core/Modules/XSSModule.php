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

class XSSModule extends Module
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

			$this->buildGETURI($links, Lang::get('string.payload_xss'));

			$this->buildPostFormParams($links, Lang::get('string.payload_blind_sql'));

			echo 'XSS attack'.PHP_EOL.PHP_EOL;

			//$this->attackGet($scan);

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

			$execution_time = ($time_end - $time_start)/60;

			if($res !== 'default'){
				if (strcmp($this->getBaseContent($this->url), $res->getBody())) {
					
					$params = Utils::filterGetUrl($value);

					$this->properties['parameter'] = $params[0];
					$this->properties['execution_time'] = $execution_time;
					$this->properties['module_name'] = Lang::get('string.XSS.module');
					$this->properties['risk'] = Lang::get('string.XSS.risk');
					$this->properties['wasc_id'] = Lang::get('string.XSS.wasc_id');

					$xss_array = explode("=", $value);
					$xss_url = explode("?", $xss_array[0]);
					$xss_attack = urldecode($xss_array[1]);

					$this->properties['target'] =  $xss_url[0];
					$this->properties['attack'] = $xss_attack;

					if($this->find_xss($res, $xss_attack)) {
						
						$this->properties['error'] = 'This webpage is vulnerable for Cross site scripting';

						if(!is_null($scan)){
							$scanDetail = new Object;
							$scanDetail->scan_id = $scan[0]->id;
							$scanDetail->properties = $this->properties;
							ScanDetail::store($scanDetail);
						}
					}
				}

				Log::info('Time: ' . $execution_time);
				Log::info('----------------- Response Code -------------------------' . PHP_EOL);
				Log::info('Request url: ' . $value);
				Log::info('response: ' . $res->getStatusCode() . PHP_EOL);
				Log::info('----------------- Content -------------------------' . PHP_EOL);
				Log::info('Content: ' .PHP_EOL. addslashes($res->getBody()) . PHP_EOL);

			}		
		}
	}

	protected function attackPost($scan)
	{
		
	}

	protected function find_xss($res, $str)
	{

		$html = (string) $res->getBody();

		$str = substr($str, 0, 6);

		$pos = strpos($html, $str);

		if($pos !== false){
			return true;
		}

		return false;
		
	}

}