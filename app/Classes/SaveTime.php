<?php

namespace App\Classes;

use App\Time;
use App\TimeLeft;
use DateTime;
use DateInterval;
use DatePeriod;

class SaveTime
{
    public function time($user, $firstDay, $day, $numOfDays, $fromDatabase, $getLastDay)
    {
        $begin = new DateTime($firstDay);
        $begin = $begin->modify('+1 day');

        $end = new DateTime($firstDay);
        $end = $end->modify('+1 day');
        $end = $end->modify('+' . $numOfDays . ' days');

        $interval = new DateInterval('P1D');

        $period = new DatePeriod($begin, $interval, $end);

        function getHours($day, $hour, $user)
        {
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
                array_push($array, array('timestamp' => $begin->format('Y-m-d H:i'), 'employer_id' => 1));
                $begin = $begin->modify('+30 minutes');
            }
            return array(
                'array' => $array,
                'minutes_left' => array(
                    'start' => $beginOriginal->format('Y-m-d H:i'),
                    'close' => $end->format('Y-m-d H:i'),
                    'max_available_minutes' => $minutes_open,
                    'company_id' => $user->company_id,
                    'employer_id' => $user->id
                )
            );
        }

        function checkDay($d, $fromDatabase)
        {
            if ($fromDatabase) {
                return ($d !== '-') ? true : false;
            }
            return ((boolean) $d->start) ? true : false;
        }

        // Boolean values for open days
        $weekDaysOpen = array();

        foreach ((array) $day as $value) {
            array_push($weekDaysOpen, checkDay($value, $fromDatabase));
        }

        $weekDayNumbers = array(0,1,2,3,4,5,6);

        // Actual opening hours
        $days = array($day->mon,$day->tue,$day->wed,$day->thu,$day->fri,$day->sat,$day->sun);

        $hours = array();

        $minutes_left = array();

        foreach ($period as $dt) {
            $dayOfWeek = date('N', strtotime($dt->format('Y-m-d')));

            $weekDay = array_search($dayOfWeek-1, $weekDayNumbers);
            $isOpen = $weekDaysOpen[$weekDay];
            if ($weekDay >= 0 && $weekDay < 7 && $isOpen) {
                $currentDay = getHours($dt, $days[$weekDay], $user);
                array_push($hours, $currentDay['array']);
                array_push($minutes_left, $currentDay['minutes_left']);
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

        if ($getLastDay) {
            $last_day = (object) end($myMinutes);
            return $last_day;
        }
        return true;
    }
}
