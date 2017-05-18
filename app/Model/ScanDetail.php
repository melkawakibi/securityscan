<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ScanDetail extends Model
{
	protected $table = "scan_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','message','sql_inj','thread','thread_level',
    ];

    protected $guarded = [
        'id','website_id', 'scan_key',
    ];

    public function scan(){
    	return $this->hasOne('App\Model\Scan', 'foreign_key');
    }
    

}
