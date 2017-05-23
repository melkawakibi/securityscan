<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
	protected $table = "scans";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type','date',
    ];

    protected $guarded = [
        'id','website_id',
    ];

    public function website(){
        return $this->hasOne('App\Model\Website', 'foreign_key');
    }

    public function scandetails(){
        return $this->hasMany('App\Model\ScanDetail');
    }

    public function modules(){
        return $this->hasMany('App\Model\Module');
    }

}
