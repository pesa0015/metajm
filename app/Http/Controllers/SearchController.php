<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\companies;
use App\TimeLeft;
use App\Category;
use App\Service;
use DB;

class SearchController extends Controller
{
    public function liveSearch(Request $request)
    {
        $search = $request->search;
        $companies = companies::where('name', 'LIKE', "%{$search}%")->get();
        $categories = Category::where('name', 'LIKE', "%{$search}%")->get();
        return response()->json(['companies' => $companies, 'categories' => $categories]);
    }

    public function mainSearch(Request $request)
    {
        $search = $request->search;
        $businesses = array('hair' => 'frisör', 'nails' => 'nagelvård');

        $foundBusiness = array_search($search, $businesses);
        if ($foundBusiness) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = $request->radius;
            $companies = DB::table('companies')
                        ->select(DB::raw("id, name, lat, lng, postal_code, city, address, ( 3959 * acos( cos( radians({$lat}) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( lat ) ) ) ) AS distance"))
                        ->where($foundBusiness, '=', 1)
                        ->having('distance', '<', $radius)->get();
            return response()->json(['companies' => $companies, 'show_google_maps' => true]);
        }
        $category = Category::where('name', $search)->get();
        if (!$category->isEmpty()) {
            $companies = companies::select('companies.*')
                                  ->join('services', 'companies.id', '=', 'services.company_id')
                                  ->join('categories', 'services.id', '=', 'services.category_id')
                                  ->join('companies_employers_services', 'services.id', '=', 'companies_employers_services.service_id')
                                  ->where('categories.name', $search)
                                  ->groupBy('companies_employers_services.company_id')
                                  ->get();
            return response()->json(['companies' => $companies, 'show_google_maps' => true]);
        }
        $company = companies::where('name', $search)->get();
        if ($company) {
            $services = \App\CompanyEmployerService::where('companies_employers_services.company_id', $company[0]->id)
                    ->join('services', 'companies_employers_services.service_id', '=', 'services.id')
                    ->join('categories', 'services.category_id', '=', 'categories.id')
                    ->get(['services.*', 'categories.name AS category']);
            $serviceWithShortestTime = \App\CompanyEmployerService::where('companies_employers_services.company_id', $company[0]->id)->join('services', 'service_id', '=', 'services.id')->orderBy('services.time', 'ASC')->first();
            $days = TimeLeft::select('start')->where('company_id', 1)->where('max_available_minutes', '>=', 60)->groupBy('start')->get();
            $day = DB::table('time_left')->select(DB::raw('MIN(start) AS open, MAX(close) AS close'))->where('company_id', $company[0]->id)->whereRaw('DATE(start) = CURDATE()')->get();
            return response()->json(['go_to_company' => true, 'company' => $company, 'services' => $services, 'days_available' => $days, 'day' => $day]);
        }
    }

    public function getCompanies(Request $request)
    {
        $search = $request;
        return response()->json($search->search);
    }

    public function existingServices(Request $request)
    {
        
    }

    public function category(Request $request)
    {
        $search = $request->term;
        $categories = Category::where('name', 'LIKE', "%{$search}%")->get();
        if (!$categories->isEmpty())
            return response()->json($categories);
        else 
            return response()->json([0 => array('id' => $search, 'name' => $search)]);
    }
}
