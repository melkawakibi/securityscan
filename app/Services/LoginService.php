<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Model\ClientService;

class LoginService{

	public function __construct($request, $client, $credentials, $links){

		$this->request = $request;
		$this->client = $client;
		$this->cs = new ClientService($request, $client);
		$this->credentials = $credentials;
		$this->links = $links;
		$this->inputfields = array();
		$this->formValues = array();
	}

	public function login($request){

		if($this->checkIfFormExists($request) && $this->checkIfLoginForm($request)){
			
			$form = $this->setupLogin($request);

			$this->request = $this->client->submit($form);

			$this->request = $this->client->request('GET', $this->request->getUri());
			
			$this->cs = new ClientService($this->request, $this->client);

		}else{

			$this->request = $this->redirectToLogin($request);

			if($this->checkIfFormExists($this->request) && $this->checkIfLoginForm($this->request)){
				
				$form = $this->setupLogin($this->request);

				print_r($this->formValues);

				$this->request = $this->client->submit($form);

				LOG::info($this->request->getUri());

				$this->request = $this->client->request('GET', $this->request->getUri());

				$this->cs = new ClientService($this->request, $this->client);
			
				LOG::info($this->cs->getContent());
			}
		}
	}

	public function checkIfFormExists($request){

		try {
		    if($request->filter('form')->form()){
		    	return true;
			}
		} catch (\InvalidArgumentException $e) {
			
		}

	}

	public function checkIfLoginForm($request){
		

		$this->request->filter('input[type=text]')->each(function ($node) {

			if(preg_match('/user/', $node->attr('name')) || preg_match('/pass/', $node->attr('name'))){
					array_push($this->inputfields, $node->attr('name'));	
			}

		});

		if(!empty($this->inputfields)){
			return true;
		}else{
			return false;
		}

	}

	public function checkIfLoginExist(){

		foreach ($this->links as $value) {
			if(strpos($value, 'login') !== false){
				return true;		
			}
		}

		return false;
	}

	public function redirectToLogin($request){
		
		if($this->checkIfLoginExist($request)){
			return $this->request = $this->client->click($this->request->selectLink('login')->link());
		}
	}

	public function setupLogin($request){

		$this->getFormValues($request);

		$formSubmit = $this->request->selectButton($this->formValues[0])->form();
		$formSubmit[$this->formValues[1]] = $this->credentials['username'];
		$formSubmit[$this->formValues[2]] = $this->credentials['password'];

		return $formSubmit;
	}

	public function getFormValues($request){

		if($this->checkIfLoginForm($request)){

			$this->request->filter('input[type=submit]')->each(function ($node) {
				array_push($this->formValues, $node->attr('value'));
			});

			$this->request->filter('input[type=text]')->each(function ($node) {
				array_push($this->formValues, $node->attr('name'));
			});

			$this->request->filter('input[type=password]')->each(function ($node) {
				array_push($this->formValues, $node->attr('name'));
			});
		}
	}
}	