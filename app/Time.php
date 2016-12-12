<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
	public $timestamps = false;
    protected $fillable = ['timestamp', 'booking_id', 'employer_id'];

    public function booking()
    {
    	return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function employers()
    {
    	return $this->belongsTo('App\CompanyEmployer', 'employer_id');
    }
}
