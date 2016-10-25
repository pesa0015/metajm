<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
	public $timestamps = false;
    protected $fillable = ['booked_at', 'payment', 'booked_by_user', 'service_id', 'company_id', 'employer_id'];

    public function company()
    {
    	return $this->hasMany('App\Time');
    }
}
