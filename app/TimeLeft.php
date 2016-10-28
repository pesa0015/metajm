<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeLeft extends Model
{
    protected $table = 'time_left';
    
	public $timestamps = false;
    protected $fillable = ['start', 'close', 'max_available_minutes', 'company_id', 'employer_id'];

    public function company()
    {
    	return $this->belongsTo('App\companies');
    }

    public function employer()
    {
    	return $this->belongsTo('App\companies_employers');
    }
}
