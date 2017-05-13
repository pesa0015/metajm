<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'price', 'time', 'category_id', 'company_id'];

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
}
