<?php

namespace App\Core;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;

class BaseClient extends Client
{

	private $content;
	private $statusCode;
	private $headers = array();

	public function setContent($content){
		$this->content = $content;
	}

	public function setStatusCode($statusCode){
		$this->statusCode = $statusCode;
	}


	public function setHeaders($headers){
		$this->headers = $headers;
	}


    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function doRequest($request)
    {
        return new Response($this->content, $this->statusCode, $this->headers);
    }

}