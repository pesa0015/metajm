<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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
        $services = \App\CompanyEmployerService::join('services', 'companies_employers_services.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->join('companies', 'services.category_id', '=', 'companies.id')
                    ->select('services.*', 'categories.name AS category')
                    ->where('companies_employers_services.company_id', $companyId)
                    ->groupBy('companies_employers_services.service_id')
                    ->get();
        return response()->json(['company' => $company, 'services' => $services, 'employers' => $employers]);
    }

    public function getTimesAndEmployers(Request $request)
    {
        $companyId = $request->company_id;
        $serviceId = $request->service_id;

        $service = \App\Service::find($serviceId);

        $days = \App\TimeLeft::join('companies_employers_services', 'time_left.employer_id', '=', 'companies_employers_services.employer_id')
                    ->join('services', 'companies_employers_services.employer_id', '=', 'companies_employers_services.employer_id')
                    ->where('companies_employers_services.service_id', $serviceId)
                    ->where('time_left.max_available_minutes', '>=', $service->time)
                    ->get();
                    
        $employers = \App\CompanyEmployerService::with('employer')->where('service_id', $serviceId)->get();

        return response()->json(['employers' => $employers, 'days' => $days]);
    }

    public function getHours(Request $request)
    {
        $date = $request->date;
        $company_id = $request->company_id;
        $service_id = $request->service_id;
        $employer_id = $request->employer_id;

        $conditions = array(
                'companies_employers_services.service_id' => $service_id, 
                'companies_employers_services.company_id' => $company_id
                );

        if (is_numeric($employer_id)) 
            $conditions['companies_employers_services.employer_id'] = $employer_id;

        $times = \App\Time::join('companies_employers_services', 'times.employer_id', '=', 'companies_employers_services.employer_id')
                    ->join('services', 'companies_employers_services.employer_id', '=', 'companies_employers_services.employer_id')
                    ->where($conditions)
                    ->whereDate('times.timestamp', '=', $date)
                    ->whereNull('booking_id')
                    ->groupBy('times.timestamp')
                    ->get(['times.timestamp']);

        return response()->json(['times' => $times, 'success' => true]);
    }
}
