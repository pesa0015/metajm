<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Time;
use App\TimeLeft;
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
		$selectTimes = array(30, 60, 90, 120, 150, 180);
		return view('company.services', [
			'services' => $services, 
			'my_services' => $services, 
			'myServicesArray' => $myServicesArray, 
			'selectTimes' => $selectTimes,
			]);
	}

	public function showOpeningHours()
	{
		\App::setLocale('sv');
		$days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		$my_days_json = companies_employers::select('default_opening_hours AS hours')->where('email', [Auth::user()->email])->first();
		$my_days = json_decode($my_days_json->hours);
		$days_open = array();
		if ($my_days) {
			foreach ($my_days as $day) {
				if ($day !== '-') {
					array_push($days_open, 'checked');
				}
				else {
					array_push($days_open, false);
				}
			}
		}
		else 
			$days_open = array('checked','checked','checked','checked','checked',false,false);

		$last_day = TimeLeft::where('employer_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
		$day = false;
		if ($last_day) {
			$last_day = $last_day->close;
			// format day
			$day = trans(strftime('days.%A', strtotime($last_day))) . ', ';
			// format day of the month
			$day .= strftime('%e', strtotime($last_day)) . ' ';
			// format month
			$day .= trans(strftime('months.%B', strtotime($last_day))) . ' kl. ';
			// format hour
			$day .= substr($last_day, 10, 6);
		}
		return view('company.opening_hours', ['days' => $days, 'days_open' => $days_open, 'last_day' => $day, 'locale' => \App::getLocale()]);
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
			return response()->json(['success' => true]);

		$today = new DateTime(date('Y-m-d'));
		$end = new DateTime(date('Y-m-d'));

		$interval = new DateInterval('P1D');

		$period = new DatePeriod($today, $interval, $end->modify('+' . $day->repeat_weeks . ' week +' . (7 - date('N') + 1) . ' days'));

		function getDayTimes($day, $hour) {
			$beginOriginal = new DateTime($day->format('Y-m-d') . ' ' . $hour->start);
			$begin = new DateTime($day->format('Y-m-d') . ' ' . $hour->start);
			$end = new DateTime($day->format('Y-m-d') . ' ' . $hour->end);

			$hourDiff = $begin->diff($end);
			$minutes_open = $hourDiff->h * 60;

			if ($hourDiff->i == 30) {
				$hourDiff->h++;
				$minutes_open += 30;
			}

			$array = array();

			for ($i = 1; $i <= $hourDiff->h*2; $i++) {
				array_push($array,array('timestamp' => $begin->format('Y-m-d H:i'), 'employer_id' => Auth::user()->id));
				$begin = $begin->modify('+30 minutes');
			}
			return array('array' => $array, 'minutes_left' => array('start' => $beginOriginal->format('Y-m-d H:i'), 'close' => $end->format('Y-m-d H:i'), 'max_available_minutes' => $minutes_open, 'company_id' => Auth::user()->company->id, 'employer_id' => Auth::user()->id));
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

		// Boolean values for open days
        $weekDaysOpen = array($monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday);

        // Actual opening hours
        $days = array($day->mon,$day->tue,$day->wed,$day->thu,$day->fri,$day->sat,$day->sun);

		$hours = array();

		$minutes_left = array();

		foreach($period as $dt) {
			$dayOfWeek = date('N', strtotime($dt->format('Y-m-d')));

			$weekDay = array_search($dayOfWeek-1,$weekDaysOpen);
			$isOpen = $weekDaysOpen[$weekDay];
			if ($weekDay >= 0 && $weekDay < 7 && $isOpen) {
                $today = getDayTimes($dt,$days[$weekDay]);
                array_push($hours, $today['array']);
                array_push($minutes_left, $today['minutes_left']);
            }
		}
		$myHours = array();
		$myMinutes = array();
		foreach ($hours as $hour) {
			foreach ($hour as $currentHour) {
				array_push($myHours, $currentHour);
			}
		}
		foreach ($minutes_left as $minutes) {
			array_push($myMinutes, $minutes);
		}
		Time::insert($myHours);
		TimeLeft::insert($myMinutes);
		$last_day = end($myMinutes);
		return response()->json(['success' => true, 'last_day' => $last_day['close']]);
    }
}
