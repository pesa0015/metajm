<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Requests;
use App\Time;
use App\companies_employers;
use DB;
use DateTime;
use DateInterval;
use DatePeriod;

class AddTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add_times';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $employers = DB::table('times')
                        ->join('companies_employers', 'times.employer_id', '=', 'companies_employers.id')
                        ->select(DB::raw("times.employer_id AS id, companies_employers.repeat_weeks * 7 - (DATEDIFF(MAX(timestamp), CURDATE())) AS days_left, MAX(timestamp) AS last_day, companies_employers.repeat_weeks AS weeks, default_opening_hours"))
                        ->groupBy('companies_employers.id')
                        ->orderBy('times.id')->get();
        
        function getDayTimes($day, $hour, $employer_id) {
            $begin = new DateTime($day->format('Y-m-d') . ' ' . explode('-', $hour)[0]);
            $end = new DateTime($day->format('Y-m-d') . ' ' . explode('-', $hour)[1]);

            $hourDiff = $begin->diff($end);

            if ($hourDiff->i == 30)
                $hourDiff->h++;

            $array = array();

            for ($i = 1; $i <= $hourDiff->h*2; $i++) {
                array_push($array,array('timestamp' => $begin->format('Y-m-d H:i'), 'employer_id' => $employer_id));
                $begin = $begin->modify('+30 minutes');
            }
            return $array;
        }

        function checkDay($d) {
            return ($d !== '-') ? true : false;
        }

        foreach($employers as $employer) {
            // if ($employer->days_left > 7)
                // continue;

            $begin = new DateTime($employer->last_day);
            $begin = $begin->modify('+1 day');
            $end = new DateTime($employer->last_day);
            $end = $end->modify('+' . $employer->days_left . ' day');

            $interval = new DateInterval('P1D');
            $period = new DatePeriod($begin, $interval, $end);

            $day = json_decode($employer->default_opening_hours);

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
                    $today = getDayTimes($dt,$day->mon,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Tuesday' && $tuesday) {
                    $today = getDayTimes($dt,$day->tue,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Wednesday' && $wednesday) {
                    $today = getDayTimes($dt,$day->wed,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Thursday' && $thursday) {
                    $today = getDayTimes($dt,$day->thu,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Friday' && $friday) {
                    $today = getDayTimes($dt,$day->fri,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Saturday' && $saturday) {
                    $today = getDayTimes($dt,$day->sat,$employer->id);
                    array_push($hours, $today);
                }
                if ($dayOfWeek === 'Sunday' && $sunday) {
                    $today = getDayTimes($dt,$day->sun,$employer->id);
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
        }
    }
}
