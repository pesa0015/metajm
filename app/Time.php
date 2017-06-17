<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    public $timestamps = false;
    protected $fillable = ['timestamp', 'booking_id', 'stylist_id'];

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function stylists()
    {
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }
}
