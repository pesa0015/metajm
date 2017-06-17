<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Time;
use App\TimeLeft;
use App\Service;
use App\StylistService;
use App\Company;
use App\Stylist;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;

class StylistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start()
    {
        $user = Stylist::with('company')->find(Auth::user()->id);
        return view('company.start', ['user' => $user]);
    }

    public function showServices()
    {
        $services = Service::with('company')->get();

        $my_services = StylistService::with('stylist')
                                             ->with('service')
                                             ->where('stylist_id', Auth::user()->id)
                                             ->get();
        if ($my_services) {
            $myServicesArray = array();
            foreach ($my_services as $my_service) {
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
        $my_days_json = Stylist::select('default_opening_hours AS hours')
                                       ->where('email', [Auth::user()->email])
                                       ->first();
        $my_days = json_decode($my_days_json->hours);
        $days_open = array();
        if ($my_days) {
            foreach ($my_days as $day) {
                if ($day !== '-') {
                    array_push($days_open, 'checked');
                } else {
                    array_push($days_open, false);
                }
            }
        } else {
            $days_open = array('checked','checked','checked','checked','checked',false,false);
        }

        $last_day = TimeLeft::where('stylist_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
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
        return view(
            'company.opening_hours',
            ['days' => $days, 'days_open' => $days_open, 'last_day' => $day, 'locale' => \App::getLocale()]
        );
    }

    public function setOpeninghours(Request $request)
    {
        function isOpen($d)
        {
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
        $stylist = Stylist::find(Auth::user()->id);
        $stylist->default_opening_hours = $default;
        if ($day->repeat_weeks) {
            $stylist->repeat_weeks = $day->repeat_weeks;
        } else {
            $stylist->repeat_weeks = null;
        }
        $time = time::with('stylists')->where('stylist_id', Auth::user()->id)->get();
        $stylist->save();
        if (!$time->isEmpty()) {
            return response()->json(['success' => true]);
        }

        $save = new \App\Classes\SaveTime;
        $user = (object) array(
            'company_id' => 1,
            'id' => 1
        );
        $last_day = $save->time($user, date('Y-m-d'), $day, $day->repeat_weeks * 7, false, true);

        return response()->json(['success' => true, 'last_day' => $last_day->close]);
    }
}
