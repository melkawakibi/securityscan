<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

	protected $table = "reports";

	public function scan()
	{
		return $this->hasOne('App\Model\Scan', 'foreign_key');
	}

}