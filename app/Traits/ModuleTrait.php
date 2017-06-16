<?php

namespace App\Traits;

use App\Model\Module;

trait ModuleTrait{

	//Create headers
	public function createModule($scan_id, $options){

		$module = new Module;
		$module->scan_id = $scan_id;

		if(!array_filter($options)){

			$module->default = 1;

		}else{

			if(!empty($options['s'])){
				$module->sql = 1;				
			}

			if(!empty($options['x'])){
				$module->xss = 1;				
			}
		}

		$module->save();

		return $module;

	}

	public function findAll(){
		return Module::all();
	}

	public function findAllByModuleId($id){
		
	}

	public function findOneById($id){
		
	}

	public function findOneByName($name){

	}

	public function numRowByName($name){
			
	}
}