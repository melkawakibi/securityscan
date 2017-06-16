<?php

namespace App\DB;

use App\Model\Scan;
use App\Traits\ModuleTrait;
use App\Traits\ScanDetailTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScanDB{

	use ModuleTrait;
	use ScanDetailTrait;

	public function __construct(){

	}

	public function create($website_id, $scan_key){
		
		$scan = new Scan;

		$scan->date = Carbon::now();
		$scan->website_id = $website_id;
		$scan->scan_key = $scan_key;

		$scan->save();
		
		return $scan;
	}

	public function findAll(){
		return Scan::all();
	}

	public function findAllByScanId($id){
		return Website::Where(['id' => $id])->get();
	}

	public function findOneByName($name){

	}

	public function numRowByUrl($url){
		return null;
	}

}