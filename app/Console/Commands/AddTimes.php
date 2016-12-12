<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Requests;
use App\Time;
use App\TimeLeft;
use App\companies_employers;
use App\Classes\SaveTime;
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
        $employers = TimeLeft::join('companies_employers', 'time_left.employer_id', '=', 'companies_employers.id')
                             ->select(DB::raw("time_left.employer_id AS id, time_left.company_id, companies_employers.repeat_weeks * 7 - (DATEDIFF(MAX(close), CURDATE())) AS days_left, MAX(close) AS last_day, companies_employers.repeat_weeks AS weeks, default_opening_hours"))
                             ->groupBy('companies_employers.id')
                             ->orderBy('time_left.id')
                             ->get();
        
        function getDayTimes($day, $hour, $employer_id, $company_id) {
            $beginOriginal = new DateTime($day->format('Y-m-d') . ' ' . explode('-', $hour)[0]);
            $begin = new DateTime($day->format('Y-m-d') . ' ' . explode('-', $hour)[0]);
            $end = new DateTime($day->format('Y-m-d') . ' ' . explode('-', $hour)[1]);

            $hourDiff = $begin->diff($end);
            $minutes_open = $hourDiff->h * 60;

            if ($hourDiff->i == 30) {
                $hourDiff->h++;
                $minutes_open += 30;
            }

            $array = array();

            for ($i = 1; $i <= $hourDiff->h*2; $i++) {
                array_push($array,array('timestamp' => $begin->format('Y-m-d H:i'), 'employer_id' => $employer_id));
                $begin = $begin->modify('+30 minutes');
            }
            return array('array' => $array, 'minutes_left' => array('start' => $beginOriginal->format('Y-m-d H:i'), 'close' => $end->format('Y-m-d H:i'), 'max_available_minutes' => $minutes_open, 'company_id' => $company_id, 'employer_id' => $employer_id));
        }

        function checkDay($d) {
            return ($d !== '-') ? true : false;
        }

        foreach($employers as $employer) {
            $save = new SaveTime;
            $user = (object) array(
                    'company_id' => $employer->company_id, 
                    'id' => $employer->id
                );
            $day = json_decode($employer->default_opening_hours);
            $save->time($user, $employer->last_day, $day, $employer->days_left, true, true);
        }
    }
}
