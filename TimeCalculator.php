<?php

class TimeCalculator
{
    static function timeToSeconds($time): int
    {
        list($hours, $minutes, $seconds) = explode(':', $time);

        return mktime($hours, $minutes, $seconds) - mktime(0, 0, 0);
    }

    static function secondsToTime($seconds): string
    {
        $hours = (int) ($seconds / 3600);
        $minutes = (int) (($seconds - ($hours * 3600)) / 60);
        $seconds = $seconds - $hours * 3600 - $minutes * 60;

        return $hours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }

    static function sumTime($first, $second): string
    {
        return self::secondsToTime(self::timeToSeconds($first) + self::timeToSeconds($second));
    }
}
