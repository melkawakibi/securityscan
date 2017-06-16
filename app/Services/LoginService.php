<?php

namespace App\Services;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Model\Client;
use Illuminate\Support\Facades\Log;

class LoginService{

	public function __construct($request, $client, $credentials, $links){

		$this->request = $request;
		$this->client = $client;
		$this->cs = new ClientService($request, $client);
		$this->credentials = $credentials;
		$this->links = $links;
		$this->inputfields = array();
		$this->formValues = array();
		$this->loginForms = array();
	}

	public function login($request){


		if($this->checkIfFormExists($request) && $this->checkIfLoginForm($request)){
			
			$form = $this->setupLogin($request);

			$this->request = $this->client->submit($form);

			$this->request = $this->client->request('GET', $this->request->getUri());
			
			$this->cs = new ClientService($this->request, $this->client);

			return true;

		}else{

			$this->request = $this->redirectToLogin($request);

			LOG::info($this->request->getUri());

			if($this->checkIfFormExists($this->request) && $this->checkIfLoginForm($this->request)){
				
				$form = $this->setupLogin($this->request);

				print_r($this->formValues);

				$this->request = $this->client->submit($form);

				LOG::info($this->request->getUri());

				$this->request = $this->client->request('GET', $this->request->getUri());

				$this->cs = new ClientService($this->request, $this->client);
				
				return true;

			}else{

				return false;
			}
		}
	}

	public function checkIfFormExists($request){

		try {

			return $request->filter('form')->count();

		} catch (\InvalidArgumentException $e) {
			
		}

	}

	public function getLoginForms($request){

		$forms = $request->filter('form');

		foreach($forms as $key => $value) {

   			$this->loginForms[] = array(spl_object_hash($value) => $value->textContent);

		}

		return $this->loginForms;
	}

	public function checkIfLoginForm($request){

		$request->filter('input[type=text]')->each(function ($node) {

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
		}else{
			//TODO handle this request if redirect fails.
			return $request;
		}
	}

	public function setupLogin($request){

		$this->getFormValues($request);

		// foreach ($this->formValues as $key => $value) {
		// 	echo $value.PHP_EOL;
		// }

		$formSubmit = $this->request->selectButton($this->formValues[0])->form();
		$formSubmit[$this->formValues[1]] = $this->credentials['username'];
		$formSubmit[$this->formValues[2]] = $this->credentials['password'];

		return $formSubmit;
	}

	public function getFormValues($request){
		
		$this->forms = $this->getLoginForms($request);

		for($i = 0; $i < sizeof($this->forms); $i++){
			foreach ($this->forms[$i] as $key => $value) {
				var_dump($value);
			}
			
		}

		if($this->checkIfLoginForm($request)){

			$this->request->filter('input[type=text]')->each(function ($node) {
				if($this->inputfields[0] === $node->attr('name') && $this->inputfields[1] === $node->attr('name')){

						array_push($this->formValues, $node->attr('name'));

				}
			});

			$this->request->filter('input[type=submit]')->each(function ($node) {
				
					//var_dump(spl_object_hash($node));

					array_push($this->formValues, $node->attr('value'));
				
				
			});

			$this->request->filter('input[type=password]')->each(function ($node) {
				array_push($this->formValues, $node->attr('name'));
			});
		}
	}
}	