<?php

namespace App\Core;

use App\Core\BaseClient;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Services\LinkService as Link;
use App\Model\Field;
use App\Core\Utils;
use Illuminate\Support\Facades\Log;

class Crawler
{
	private $crawler;
	private $client;
	private $url;
	private $submits = array();
	private $forms = array();
	private $paramPOST = array();
	private $paramGET = array();

	public function __construct()
	{
		$this->client = new BaseClient;
	}

	public function createCrawler($pageInfo)
	{
		$this->headers = Utils::headerToArray($pageInfo->header);

		$this->client->setContent($pageInfo->content);
		$this->client->setStatusCode($pageInfo->http_status_code);
		$this->client->setHeaders($this->headers);

		$this->crawler = $this->client->request('GET', $pageInfo->url);
	}

	public function getFormsParams($url)
	{

		$this->url = $url;

		$this->crawler->filter('input[type=submit]')->each(function(DomCrawler $node){

			if(!in_array($node->attr('value'), $this->submits)){
				
				array_push($this->submits, $node->attr('value'));

				$this->forms[] = $this->crawler->selectButton($node->attr('value'))->form();
			}

		});

		if(!empty($this->forms)){

			foreach ($this->forms as $form) {

				$fields = $form->all();

					if(!empty($fields)){

					if($form->getUri() === $url){

						$link = Link::findOneByLinkUrl($url);

						if(!is_null($link)){
							foreach ($fields as $field) {

								$fieldObj = new Field($field);

								$type = $fieldObj->getType();

								if($type === 'text' || $type === 'password'){
									array_push($this->paramPOST, ['id' => $link[0]->id, 'param' => $field->getName()]);
								}
							}
						}
					}
				}
			}
		}

		return $this->paramPOST;

	}


	public function getURIParams($url){

		$params = Utils::filterGetUrl($url);

		if(!empty($params)){

			$link = Link::findOneByLinkUrl($url);

			if(!is_null($link)){
				
				foreach ($params as $param) {

					$param = (object) $param;

					if(!empty($param)){
						array_push($this->paramGET, ['id' => $link[0]->id, 'param' => $param->scalar]);
					}
				}
			}
		}

		//print_r($this->paramGET);

		return $this->paramGET;

	}

}