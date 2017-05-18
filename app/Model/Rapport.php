<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
	protected $table = "rapports";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id','website_id',
    ];

    public function website(){
    	return $this->hasOne('App\Model\Website', 'foreign_key');
    }

}
