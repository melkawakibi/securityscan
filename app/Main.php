<?php

namespace App;

use App\Core\Crawler as Crawler;
use App\Core\Modules\SQLModule as SQL;
use App\Model\Website;
use Illuminate\Support\Facades\Log;

class Main{

	private $scraper;
	private $url;
	private $server;
	private $modules;
	private $sql;

	public function __construct($url, $options){

		if(filter_var($url, FILTER_VALIDATE_URL)){
			echo 'Creating target: ' . $url . PHP_EOL;
			$this->url = $url;
		}else{
			echo 'invalid url, try again' . PHP_EOL;
			exit();
		}

		$credentials = null;

		if(!empty($options['u'])){
			$credentials = [ 'username' => $options['u'] , 'password' => $options['p'] ];
		}

		//initiate scraper
		$this->crawler= new Crawler;
		$this->crawler->setup($this->url);
		$this->crawler->crawl($credentials);

		$this->prepare($options);
		$this->scan();

	}

	public function prepare($options){

		if(!array_filter($options)){
			//TODO set default scan
		}

		if($options['s']){
			$this->sql = new SQL($this->url);
		}

		if($options['x']){
			//XSS MODULE
		}

	}

	public function scan(){

		if($this->sql instanceof SQL){
			$this->sql->attack();
		}

	}

}