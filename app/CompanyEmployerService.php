<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyEmployerService extends Model
{
    public $timestamps = false;
    public $table = 'companies_employers_services';
    protected $fillable = ['company_id', 'employer_id', 'service_id', 'company_id'];

    public function employer()
    {
        return $this->belongsTo('App\companies_employers', 'employer_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
}
