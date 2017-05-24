<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
	protected $table = "params";

    public function link(){
    	return $this->hasOne('App\Model\Link', 'foreign_key');
    }

}
