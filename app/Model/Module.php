<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = "modules";

    public function scan(){
        return $this->hasOne('App\Model\Scan', 'foreign_key');
    }
    

}
