<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stylist extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'admin_role',
        'company_id',
        'repeat_weeks',
        'default_opening_hours'
    ];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
}
