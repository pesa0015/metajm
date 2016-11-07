<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;
use App\Service;
use App\CompanyEmployerService;
use Auth;

class ServiceController extends Controller
{
    public function create(Request $request)
    {
    	$newServices = json_decode($request->services);

    	if (count($newServices) == 0)
    		return;

        $services = array();

    	foreach ($newServices as $newService) {
    		$categoryId = null;
    		if ((int) $newService->category > 0) {
    			$categoryId = $newService->category;
    		}
    		else {
	    		$category = new Category;
	    		$category->name = $newService->category;
	    		$category->save();
	    		$categoryId = $category->id;
	    	}
	    	array_push($services, array(
	    		'name' => $newService->description, 
	    		'price' => $newService->price, 
	    		'time' => $newService->time,
	    		'category_id' => $categoryId,
	    		'company_id' => Auth::user()->company->id
	    		)
	    	);
    	}

    	Service::insert($services);
    	return response()->json(['success' => true]);
    }

    public function edit(Request $request)
    {
        $updated_service = json_decode($request->service);
        $categoryId = null;
        if (is_numeric($updated_service->category_id)) {
            $categoryId = $updated_service->category_id;
        }
        else {
            $category = new Category;
            $category->name = $updated_service->category_id;
            $category->save();
            $categoryId = $category->id;
        }
        $service = Service::find($updated_service->id);
        $service->name = $updated_service->name;
        $service->price = $updated_service->price;
        $service->time = $updated_service->time;
        $service->category_id = $categoryId;
        $service->company_id = Auth::user()->company->id;
        $service->update();
        return response()->json(['success' => true]);
    }

    public function useService(Request $request)
    {
        $service = $request;
        if ($service->use === 'true') {
            $currentService = Service::find($service->id);
            $myService = new CompanyEmployerService;
            $myService->employer_id = Auth::user()->id;
            $myService->service_id = $currentService->id;
            $myService->company_id = Auth::user()->company->id;
            $myService->save();
        }
        else {
            $myService = CompanyEmployerService::where('employer_id', Auth::user()->id)
                                               ->where('service_id', $service->id)
                                               ->where('company_id', Auth::user()->company->id)
                                               ->delete();
        }
        return response()->json(['success' => true]);
    }
}
