<?php

function timeToSeconds($time): int
{
    list($hours, $minutes, $seconds) = explode(':', $time);

    return mktime($hours, $minutes, $seconds) - mktime(0, 0, 0);
}

function secondsToTime($seconds): string
{
    $hours = (int) ($seconds / 3600);
    $minutes = (int) (($seconds - ($hours * 3600)) / 60);
    $seconds = $seconds - $hours * 3600 - $minutes * 60;

    return $hours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" . str_pad($seconds, 2, '0', STR_PAD_LEFT);
}

function sumTime($first, $second): string
{
    return secondsToTime(timeToSeconds($first) + timeToSeconds($second));
}

function entry(): void
{
    $first = readline("Enter first time value (hh:mm:ss): ");
    $second = readline("Enter first time value (hh:mm:ss): ");

    echo "Result: " . sumTime($first, $second);
}

entry();
