<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StylistService extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['company_id', 'stylist_id', 'service_id', 'company_id'];

    public function stylist()
    {
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
}
