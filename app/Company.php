<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	public $timestamps = false;
    protected $fillable = [
    	'name', 
    	'address', 
    	'postal_code', 
    	'city', 
    	'lat', 
    	'lng', 
    	'hair', 
    	'nails', 
    	'dental', 
    	'tattoo', 
    	'tel', 
    	'mail',
    	'show_employers', 
    	'password'];

    public function company()
    {
    	return $this->hasMany('App\companies_employers');
    }
}
