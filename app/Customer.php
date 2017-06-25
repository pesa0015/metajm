<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'person_nr',
        'first_name',
        'last_name',
        'mail',
        'tel'
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }
}
