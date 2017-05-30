<?php

namespace App\Model;

class ClientService{

	public function __construct($request, $client){

		$this->request = $request;
		$this->client = $client;

	}

	public function getRequest(){
		return $this->request;
	}

	public function getUri(){
		return $this->request->getUri();
	}

	public function getBaseUri(){
		return parse_url($this->getUri(), PHP_URL_HOST);
	}

	public function getScheme(){
		return parse_url($this->getUri(), PHP_URL_SCHEME);
	}

	public function getPort(){
		return parse_url($this->getUri(), PHP_URL_PORT);
	}


	public function getResonse(){
		return $this->client->getResponse();
	}

	public function getStatus(){
		return $this->getResonse()->getStatus();
	}

	public function getHeaders(){
		return $this->getResonse()->getHeaders();
	}

	public function getServer(){

		foreach ($this->getHeaders() as $key => $value) {

			if($key === 'Server' || $key === 'server'){
				return $value[0];
			}else{
				$server = "No-server-found";
			}
		}

		return $server;
	}

	public function getContent(){
		return $this->getResonse()->getContent();
	}

	public function printInfoWebsite(){
		 Log::info('url: ' . $this->getUri());
		 Log::info('Base url: ' . $this->getBaseUri());
		 Log::info('status: ' . $this->getStatus());
		 Log::info('server: ' . $this->getServer());
		 Log::info('Date: ' . Carbon::now());
		 Log::info('headers: ' . print_r($this->getHeaders(), TRUE));
		 Log::info('links: ' . print_r($this->links(), TRUE));
		 Log::info('form links: ' . print_r($this->formLinks(), TRUE));
	}

}

