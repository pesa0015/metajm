<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeLeft extends Model
{
    protected $table = 'time_left';
    
    public $timestamps = false;
    protected $fillable = ['start', 'close', 'max_available_minutes', 'company_id', 'stylist_id'];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function stylist()
    {
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }
}
