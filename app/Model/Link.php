<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	protected $table = "links";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'methode','url',
    ];

    protected $guarded = [
        'id','website_id',
    ];

    public function website(){
    	return $this->hasOne('App\Model\Website', 'foreign_key');
    }

}
