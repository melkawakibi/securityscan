<?php

namespace App\Traits;

use App\Model\ScanDetail;

trait ScanDetailTrait{


	public function createScanDetail($scan_id, $properties){

		$scanDetail = new ScanDetail;

		$scanDetail->scan_id = $scan_id;	
		$scanDetail->module_name = $properties['module_name'];
		$scanDetail->risk = $properties['risk'];
		$scanDetail->parameter = $properties['parameter'];
		$scanDetail->attack = $properties['attack'];
		$scanDetail->error = $properties['error'];
		$scanDetail->wasc_id = $properties['wasc_id'];
		$scanDetail->execution_time = $properties['execution_time'];

		$scanDetail->save();
		return $scanDetail;
	}

	public function findAllScanDetails(){
		return ScanDetail::all();
	}

}