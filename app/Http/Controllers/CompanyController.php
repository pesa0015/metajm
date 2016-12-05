<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\companies;
use App\companies_employers;

class CompanyController extends Controller
{
	public function getServices(Request $request)
    {
        $companyId = $request->company_id;

        $company = companies::find($companyId);
        $employers = false;
        if ($company->show_employers == 1)
            $employers = companies_employers::where('company_id', $companyId)->get();
        $services = DB::table('companies_employers_services')
                    ->join('services', 'companies_employers_services.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('companies', 'services.category_id', '=', 'companies.id')
                    ->select('services.*', 'categories.name AS category')
                    ->where('companies_employers_services.company_id', $companyId)
                    ->get();
        return response()->json(['company' => $company, 'services' => $services, 'employers' => $employers]);
    }

    public function getTimesAndEmployers(Request $request)
    {
        $companyId = $request->company_id;
        $serviceId = $request->service_id;

        $service = \App\Service::find($serviceId);

        $days = DB::table('time_left')
                    ->join('companies_employers_services', 'time_left.employer_id', '=', 'companies_employers_services.employer_id')
                    ->join('services', 'companies_employers_services.employer_id', '=', 'companies_employers_services.employer_id')
                    ->where('companies_employers_services.service_id', $serviceId)
                    ->where('time_left.max_available_minutes', '>=', $service->time)
                    ->get();
                    
        $employers = \App\CompanyEmployerService::with('employer')->where('service_id', $serviceId)->get();

        return response()->json(['employers' => $employers, 'days' => $days]);
    }
}
