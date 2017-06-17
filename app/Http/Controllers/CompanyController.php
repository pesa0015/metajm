<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Company;
use App\Stylist;
use App\StylistService;
use App\TimeLeft;
use DB;

class CompanyController extends Controller
{
    public function company($search)
    {
        $company = Company::where('id', $search)->orWhere('name', $search)->first();
        $stylists = false;
        if ($company->show_stylists == 1) {
            $stylists = Stylist::where('company_id', $company->id)->get();
        }
        $services = \App\StylistService::join(
            'services',
            'stylist_services.service_id',
            '=',
            'services.id'
        )
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('companies', 'services.category_id', '=', 'companies.id')
        ->select('services.*', 'categories.name AS category')
        ->where('stylist_services.company_id', $company->id)
        ->groupBy('stylist_services.service_id')
        ->get();
        $serviceWithShortestTime = \App\StylistService::where(
            'stylist_services.company_id',
            $company->id
        )
        ->join('services', 'service_id', '=', 'services.id')
        ->orderBy('services.time', 'ASC')
        ->first();
        $days = TimeLeft::select('start')
        ->where('company_id', $company->id)
        ->where('max_available_minutes', '>=', $serviceWithShortestTime->time)
        ->groupBy('start')
        ->get();
        $day = TimeLeft::select(DB::raw('MIN(start) AS open, MAX(close) AS close'))
        ->where('company_id', $company->id)->whereDate('start', '=', 'CURDATE()')
        ->first();
        return array(
            'go_to_company' => true,
            'company' => $company,
            'services' => $services,
            'stylists' => $stylists,
            'serviceWithShortestTime' => $serviceWithShortestTime,
            'days_available' => $days,
            'day' => $day
        );
    }

    public function getCompany(Request $request)
    {
        $id = $request->company;

        return $this->company($id);
    }

    public function getServices(Request $request)
    {
        $companyId = $request->company_id;

        $company = Company::find($companyId);
        $stylists = false;
        if ($company->show_stylists == 1) {
            $stylists = Stylist::where('company_id', $companyId)->get();
        }
        $services = \App\StylistService::join(
            'services',
            'stylist_services.service_id',
            '=',
            'services.id'
        )
        ->join('categories', 'services.category_id', '=', 'categories.id')
        ->join('companies', 'services.category_id', '=', 'companies.id')
        ->select('services.*', 'categories.name AS category')
        ->where('stylist_services.company_id', $companyId)
        ->groupBy('stylist_services.service_id')
        ->get();
        return response()->json(['company' => $company, 'services' => $services, 'stylists' => $stylists]);
    }

    public function getDays(Request $request)
    {
        $company = $request->company;
        return response()->json([
                'company' => $company,
                'services' => $services,
                'serviceWithShortestTime' => $serviceWithShortestTime,
                'available_days' => $days,
                'day' => $day
            ]);
    }

    public function getTimesAndstylists(Request $request)
    {
        $companyId = $request->company_id;
        $serviceId = $request->service_id;

        $service = \App\Service::find($serviceId);

        $days = \App\TimeLeft::join(
            'stylist_services',
            'time_left.stylist_id',
            '=',
            'stylist_services.stylist_id'
        )
        ->join(
            'services',
            'stylist_services.stylist_id',
            '=',
            'stylist_services.stylist_id'
        )
        ->where('stylist_services.service_id', $serviceId)
        ->where('time_left.max_available_minutes', '>=', $service->time)
        ->get();
                    
        $stylists = \App\StylistService::with('stylist')->where('service_id', $serviceId)->get();

        return response()->json(['stylists' => $stylists, 'days' => $days]);
    }

    public function getHours(Request $request)
    {
        $date = $request->date;
        $company_id = $request->company_id;
        $service_id = $request->service_id;
        $stylist_id = $request->stylist_id;

        $conditions = array(
                'stylist_services.service_id' => $service_id,
                'stylist_services.company_id' => $company_id
                );

        if (is_numeric($stylist_id)) {
            $conditions['stylist_services.stylist_id'] = $stylist_id;
        }

        $times = \App\Time::join(
            'stylist_services',
            'times.stylist_id',
            '=',
            'stylist_services.stylist_id'
        )
        ->join(
            'services',
            'stylist_services.stylist_id',
            '=',
            'stylist_services.stylist_id'
        )
        ->where($conditions)
        ->whereDate('times.timestamp', '=', $date)
        ->whereNull('booking_id')
        ->groupBy('times.timestamp')
        ->get(['times.timestamp']);

        return response()->json(['times' => $times, 'success' => true]);
    }
}
