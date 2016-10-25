<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Time;
use App\Service;
use App\CompanyEmployerService;
use App\companies;
use App\companies_employers;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;

class EmployerController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function start()
	{
		$user = companies_employers::with('company')->find(Auth::user()->id);
		return view('company.start', ['user' => $user]);
	}

	public function showServices(){
		$services = Service::with('company')->get();

		$my_services = CompanyEmployerService::with('employer')->with('service')->where('employer_id', Auth::user()->id)->get();
		if ($my_services) {
			$myServicesArray = array();
			foreach($my_services as $my_service) {
				array_push($myServicesArray, $my_service->service->id);
			}
		}
		$selectTimes = array(0.5,1,1.5,2,2.5,3);
		return view('company.services', [
			'services' => $services, 
			'my_services' => $services, 
			'myServicesArray' => $myServicesArray, 
			'selectTimes' => $selectTimes,
			]);
	}

	public function showOpeningHours()
	{
		$days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		$my_days = companies_employers::where('email', [Auth::user()->email])->first();
		$last_day = false;
		if ($last_day) {
			return view('company.opening_hours_set', ['days' => $days, 'last_day' => $last_day, 'script' => 'js/company.opening_hours.js']);
		}
		else {
			return view('company.opening_hours_empty', ['days' => $days, 'last_day' => false, 'script' => 'js/company.opening_hours.js']);
		}
	}

	public function setOpeninghours(Request $request)
    {
    	function isOpen($d) {
			return (!empty($d->start) && !empty($d->end)) ? "\"$d->start-$d->end\"" : "\"-\"";
		}
    	$day = json_decode($request->get('days'));
    	$default = '{"mon":' . isOpen($day->mon) 
				  .',"tue":' . isOpen($day->tue) 
				  .',"wed":' . isOpen($day->wed) 
				  .',"thu":' . isOpen($day->thu) 
				  .',"fri":' . isOpen($day->fri) 
				  .',"sat":' . isOpen($day->sat) 
				  .',"sun":' . isOpen($day->sun) . '}';
		$employer = companies_employers::find(Auth::user()->id);
		$employer->default_opening_hours = $default;
		if ($day->repeat_weeks) 
			$employer->repeat_weeks = $day->repeat_weeks;
		else 
			$employer->repeat_weeks = null;
		$time = time::with('employers')->where('employer_id', Auth::user()->id)->get();
		$employer->save();
		if (!$time->isEmpty())
			return 'success';

		$today = new DateTime(date('Y-m-d'));
		$end = new DateTime(date('Y-m-d'));

		$interval = new DateInterval('P1D');

		$period = new DatePeriod($today, $interval, $end->modify('+' . $day->repeat_weeks . ' week +' . (7 - date('N') + 1) . ' days'));

		function getDayTimes($day, $hour) {
			$begin = new DateTime($day->format('Y-m-d') . ' ' . $hour->start);
			$end = new DateTime($day->format('Y-m-d') . ' ' . $hour->end);

			$hourDiff = $begin->diff($end);

			if ($hourDiff->i == 30)
				$hourDiff->h++;

			$array = array();

			for ($i = 1; $i <= $hourDiff->h*2; $i++) {
				array_push($array,array('timestamp' => $begin->format('Y-m-d H:i'), 'employer_id' => Auth::user()->id));
				$begin = $begin->modify('+30 minutes');
			}
			return $array;
		}

		function checkDay($d) {
			return ((boolean) $d->start) ? true : false;
		}

		$monday = checkDay($day->mon);
		$tuesday = checkDay($day->tue);
		$wednesday = checkDay($day->wed);
		$thursday = checkDay($day->thu);
		$friday = checkDay($day->fri);
		$saturday = checkDay($day->sat);
		$sunday = checkDay($day->sun);

		$hours = array();

		foreach($period as $dt) {
			$dayOfWeek = date('l', strtotime($dt->format('Y-m-d')));
			
			if ($dayOfWeek === 'Monday' && $monday) {
				$today = getDayTimes($dt,$day->mon);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Tuesday' && $tuesday) {
				$today = getDayTimes($dt,$day->tue);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Wednesday' && $wednesday) {
				$today = getDayTimes($dt,$day->wed);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Thursday' && $thursday) {
				$today = getDayTimes($dt,$day->thu);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Friday' && $friday) {
				$today = getDayTimes($dt,$day->fri);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Saturday' && $saturday) {
				$today = getDayTimes($dt,$day->sat);
				array_push($hours, $today);
			}
			if ($dayOfWeek === 'Sunday' && $sunday) {
				$today = getDayTimes($dt,$day->sun);
				array_push($hours, $today);
			}
		}
		$myHours = array();
		foreach ($hours as $hour) {
			foreach ($hour as $currentHour) {
				array_push($myHours, $currentHour);
			}
		}
		Time::insert($myHours);
		return 'success';
    }
}
