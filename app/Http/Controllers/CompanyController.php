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
}
