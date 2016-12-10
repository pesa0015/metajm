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
    public function isCompany($search)
    {
        $company = companies::where('id', $search)->orWhere('name', $search)->first();
        if ($company)
            return $company->id;
        return -1;
    }

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
        $company_id = $this->isCompany($search);
        if ($company_id > 0) {
            return response()->json(['company_found' => true, 'company_id' => $company_id]);
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
