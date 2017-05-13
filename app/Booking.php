<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'booked_at',
        'start',
        'end',
        'payment',
        'booked_by_user',
        'service_id',
        'company_id',
        'employer_id',
        'transaction_id',
        'payment_method',
        'booking_key'
    ];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function employer()
    {
        return $this->belongsTo('App\CompanyEmployer', 'employer_id');
    }
}
