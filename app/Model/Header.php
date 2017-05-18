<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
	protected $table = "headers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','value',
    ];

    protected $guarded = [
        'id','website_id',
    ];

    public function website(){
    	return $this->hasOne('App\Model\Website', 'foreign_key');
    }

}
