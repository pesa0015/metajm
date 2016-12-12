<?php

namespace App;

// use Illuminate\Auth\Authenticatable as Authenticatable;

// use Illuminate\Database\Eloquent\Model as Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CompanyEmployer extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	public $table = 'companies_employers';
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'admin_role', 'company_id', 'repeat_weeks', 'default_opening_hours'];
    protected $primaryKey = 'id';

    public function company()
    {
    	return $this->belongsTo('App\Company', 'company_id');
    }
}
