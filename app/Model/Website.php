<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
	protected $table = "websites";

    public function customer(){
    	return $this->hasOne('App\Model\Customer', 'foreign_key');
    }

}
