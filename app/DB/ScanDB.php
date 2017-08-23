<?php

namespace App\DB;

use App\Model\Scan;
use App\Traits\ModuleTrait;
use App\Traits\ScanDetailTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScanDB{

	use ScanDetailTrait;

	public function __construct(){

	}

	public function create($website_id){
		
		$scan = new Scan;
		
		$scan->website_id = $website_id;

		$scan->save();
		
		return $scan;
	}

	public function findAllScans(){
		return Scan::all();
	}

	public function findOneByScanId($id){
		return Scan::Where(['id' => $id])->get();
	}

	public function findLastByScanIdOrderDesc($id){
		return Scan::Where('website_id', $id)->orderBy('created_at', 'desc')->get(); 
	}

	public function findOneByName($name){

	}

	public function numRowByUrl($url){
		return null;
	}

}