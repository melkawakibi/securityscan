<?php

namespace App\Core\Modules;

use App\Services\WebsiteService as Website;
use App\Services\ScanService as Scan;
use App\Services\LinkService as Link;
use App\Services\HeaderLinkService as HeaderLink;
use App\Services\ScanDetailService as ScanDetail;
use \stdClass as Object;
use Lang;

class HeaderModule
{

	public function __construct($url)
	{
		$this->website = Website::findOneByUrl($url);
		$this->properties = array();
	}

	public function start()
	{

		echo 'Security Headers'.PHP_EOL.PHP_EOL;

		$this->scan = Scan::findLastByScanIdOrderDesc($this->website[0]->id);	
		$this->links = Link::findAllByWebsiteId($this->website[0]->id);

		foreach ($this->links as $link) {
			
			$headerLinks = HeaderLink::findAllByLinkId($link->id);

			foreach ($headerLinks as $headerLink) {
				
				if($link->id === $headerLink->link_id){

					$securityHeaders = Lang::get('string.Security_Headers');

					if(!$this->check($headerLink, $securityHeaders)){

						foreach ($securityHeaders as $key => $value) {
							
							$object = new Object;
							$object->scan_id = $this->scan[0]->id;
							$object->module = $key;
							$object->link = $link->url;
							$object->method = $link->method;

							if(!ScanDetail::numRowByScanIdAndModuleAndLinkAndMethod($object)){	
								if($value['type'] === 'Security_header'){
									$this->properties['module_name'] = $key;
									$this->properties['risk'] = $value['risk'];
									$this->properties['target'] = $link->url;
									$this->properties['parameter'] = 'n/a';
									$this->properties['attack'] = 'n/a';
									$this->properties['error'] = $value['error'];
									$this->properties['wasc_id'] = $value['wasc_id'];
									$this->properties['execution_time'] = 'n/a';
									$this->properties['method'] = $link->method;

									$scanDetail = new Object;
									$scanDetail->scan_id = $this->scan[0]->id;
									$scanDetail->properties = $this->properties;

									ScanDetail::store($scanDetail);
								}
							}
						}


					}else{
						echo 'security header found';
					}

				}

			}

		}
	}

	public function check($object, $array)
	{
		
		foreach ($array as $key => $value) {
			
			if($key === $object->name){
				return true;
			}
		}

		return false;
	}

}