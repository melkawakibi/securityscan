<?php

namespace App\Core\Modules;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\ScanDetailService as ScanDetail;
use App\Services\ParamService as Param;
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
		$this->queryArray = array();
		$this->formArray = array();
		$this->defaultlinks = array();
		$this->properties = array();
		$this->timeout = 10;
	}

	abstract public function start();

	abstract protected function attackGet($scan);

	abstract protected function attackPost($scan);

	protected function getBaseContent($url)
	{
		$res = $this->client->request('GET', $this->url);
		return $res->getBody();
	}

	protected function buildGETURI($links, $payload)
	{

		foreach ($links as $key => $link) {

			$params = Param::findAllByMethod('GET');

			if($params->isNotEmpty()){

				$params = Utils::getParamArray($params);

				$query_array = array();

				$lines = $this->getLines($payload);

				$query_array = Utils::create_comined_array_get($params, $lines);

				array_filter($query_array);

					if($link->method === 'GET'){

						foreach ($query_array as $key => $query) {
						$query = http_build_query($query);
						$url = $link->url . '?' . $query;
						array_push($this->queryArray, $url);

					}
				}

			}

		}
	}

	protected function buildPostFormParams($links, $payload)
	{

		foreach ($links as $key => $link) {

			$params = Param::findAllParamByLinkAndMethod($link->id, 'POST');

			$param = Param::findOneByLinkIdAndType($link->id);

			if($param->isNotEmpty()){

				$submit = $param->first();
				
				$submitParam = $submit->params;
				$submitValue = $submit->value;

			}

			if($params->isNotEmpty()){

				$paramArray = Utils::getParamArray($params);

				$lines = $this->getLines($payload);

				$paramCombine = Utils::create_comined_array_post($paramArray, $lines, $submitParam, $submitValue, $link->id);

				array_push($this->formArray, $paramCombine);
			}
		}
	}

	public function getLines($payload)
	{
		$lines = file(public_path() . $payload);

		$replace_str = $this->getReplaceString($payload);

		$lines = Utils::replace_string_array($lines, $replace_str[0], $replace_str[1]);

		return $lines;
	}

	public function setTimeOut($timeout)
	{

		$this->timeout = $timeout;

	}

	public function getReplaceString($payload)
	{
		if(strpos($payload, 'sqlblind') !== false){
			return array("0" => (string) $this->timeout, "1" => Lang::get('string.BlindSQL_Replace'));
		}else{
			return array("0" => Utils::generateRandomString(), "1" => Lang::get('string.XSS_Replace'));
		}
	}

}