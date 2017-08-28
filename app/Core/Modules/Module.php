<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\ParamService as Param;
use \stdClass as Object;
use App\Core\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Lang;

abstract class Module
{

	protected $url;
	protected $client;
	protected $baseUrl;
	protected $links;
	protected $formLinks;
	protected $urlWithQuery;
	protected $urlArray;
	protected $timeout;

	public function __construct($url)
	{
		$this->url = $url;
		$this->client = new GuzzleClient;
		$this->urlArray = array();
		$this->defaultlinks = array();
		$this->properties = array();
		$this->timeout = 1;
	}

	abstract public function start();

	abstract protected function attackGet($scan);

	protected function getBaseContent($url)
	{
		$res = $this->client->request('GET', $this->url);
		return $res->getBody();
	}

	protected function linkList($links, $payload)
	{

		foreach ($links as $key => $link) {

			$baseUrl = Utils::getBaseUrl($link->url);

			$params = Param::findAllByLinkId($link->id);

			print_r($params);

			$params = Utils::getParamArray($params);

			$query_array = array();

			$lines = file(public_path() . $payload);

			$replace_str = $this->getReplaceString($payload);

			$lines = Utils::replace_string_array($lines, $replace_str[0], $replace_str[1]);

			if ($link->methode === 'GET') {

				$query_array = Utils::create_comined_array($params, $lines);

				array_filter($query_array);

			}

			foreach ($query_array as $key => $query) {
				$query = http_build_query($query);
				$url = $baseUrl . '?' . $query;
				array_push($this->urlArray, $url);

			}

		}
	}

	protected function responseAnalyse($res, $str)
	{
		$response = $res->getBody();

		if (strpos($response, $str)) {
			return true;
		} else if( $response) {
			return false;
		}
	}

	public function setTimeOut($timeout)
	{

		$this->timeout = $timeout;

	}

	public function getReplaceString($payload)
	{
		if(strpos($payload, 'sqlblind') !== false){
			return array("0" => (string) $this->timeout, "1" => Lang::get('string.SQL_Replace'));
		}else{
			return array("0" => Utils::generateRandomString(), "1" => Lang::get('string.XSS_Replace'));
		}
	}

}