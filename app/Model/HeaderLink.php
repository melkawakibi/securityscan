<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HeaderLink extends Model
{
	protected $table = "header_links";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','value',
    ];

    protected $guarded = [
        'id','link_id',
    ];

    public function link(){
    	return $this->hasOne('App\Model\Link', 'foreign_key');
    }

}
