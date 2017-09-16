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

			$this->buildGETURI($links, Lang::get('string.payload_blind_sql'));

			echo 'Blind SQLI attack'.PHP_EOL.PHP_EOL;

			$this->attackGet($scan);

		}else{
			echo 'No links to scan'.PHP_EOL;
		}
	}

	protected function attackGet($scan)
	{

		foreach ($this->urlArray as $key => $value) {

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

			$params = Utils::filterGetUrl($value);

			$this->properties['parameter'] = $params[0];
			$this->properties['execution_time'] = $seconds;
			$this->properties['module_name'] = Lang::get('string.BlindSQL.module');
			$this->properties['risk'] = Lang::get('string.BlindSQL.risk');
			$this->properties['wasc_id'] = Lang::get('string.BlindSQL.wasc_id');

			$sql_array = explode("=", $value);
			$sql_url = explode("?", $sql_array[0]);
			$sql_attack = urldecode($sql_array[1]);

			$this->properties['target'] =  $sql_url[0];
			$this->properties['attack'] = $sql_attack;

			$this->properties['error'] = $value;

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
		
		
		if (filter_var($value, FILTER_VALIDATE_URL) !== false){
				
				$res = $this->client->request('POST', $value);
			}
	}



}