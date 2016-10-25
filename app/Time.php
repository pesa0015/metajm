<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
	public $timestamps = false;
    protected $fillable = ['timestamp', 'booking_id', 'employer_id'];

    public function booking()
    {
    	return $this->belongsTo('App\Booking');
    }

    public function employers()
    {
    	return $this->belongsTo('App\companies_employers', 'employer_id');
    }
}
