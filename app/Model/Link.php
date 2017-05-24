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

    public function params(){
        return $this->hasMany('App\Model\Param');
    }

}
