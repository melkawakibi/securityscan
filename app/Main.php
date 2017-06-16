<?php

namespace App;

use App\Core\Crawler as Crawler;
use App\Core\Modules\SQLModule as SQL;
use App\Core\Modules\XSSModule as XSS;
use App\Model\Website;
use App\DB\ScanDB;
use App\DB\WebsiteDB;

use Illuminate\Support\Facades\Log;

class Main{

	private $url;

	public function __construct($url, $options){

		if(filter_var($url, FILTER_VALIDATE_URL)){
			echo 'Creating target: '.$url.PHP_EOL;
			$this->url = $url;
		}else{
			echo 'invalid url, try again' . PHP_EOL;
			exit();
		}

		$credentials = null;

		if(!empty($options['u'])){
			$credentials = [ 'username' => $options['u'] , 'password' => $options['p'] ];
		}

		//initialte module variables
		$this->sql = '';
		$this->xss = '';

		//database setup
		$this->scandb = new ScanDB;
		$this->websitedb = new WebsiteDB;

		//initiate scraper
		$this->crawler= new Crawler;
		$this->crawler->setup($this->url);
		$this->crawler->crawl($credentials);

		$this->prepare($options);
		$this->scan();

	}

	public function prepare($options){

		if($options['s']){
			$this->sql = new SQL($this->url);
		}

		if($options['x']){
			$this->xss = new XSS($this->url);
		}

		$website = $this->websitedb->findOneByUrl($this->url);

		$uniqueId = rand().(string) $website[0]->id;

		//save scan to database
		$this->scan = $this->scandb->create($website[0]->id, $uniqueId);

		$this->scandb->createModule($this->scan->id, $options);

	}

	public function scan(){

		if($this->sql instanceof SQL){
			$this->sql->attack($this->scan);
		}

		if($this->xss instanceof XSS){
			$this->xss->attack($this->scan);
		}

	}

}