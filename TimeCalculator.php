<?php

var_dump($argc);
var_dump($argv);

if ($argc < 3) {
    echo 'Insufficient arguments count!';
    return;
}

echo sprintf('Result: %s', TimeCalculator::sumTime($argv[1], $argv[2]));
readline();

class TimeCalculator
{
    static array $possibleCharacters = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':' ];

    static function timeToSeconds($time): int {
        list($hours, $minutes, $seconds) = explode(':', $time);

        return mktime($hours, $minutes, $seconds) - mktime(0, 0, 0);
    }

    static function secondsToTime($seconds): string {
        $hours = (int) ($seconds / 3600);
        $minutes = (int) (($seconds - ($hours * 3600)) / 60);
        $seconds = $seconds - $hours * 3600 - $minutes * 60;
        $hours = $hours % 24;

        return $hours . ":" . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }

    static function sumTime($first, $second): string {
        foreach (str_split($first) as $char)
            if (!in_array($char, self::$possibleCharacters))
                return 'Invalid input!';

        foreach (str_split($second) as $char)
            if (!in_array($char, self::$possibleCharacters))
                return 'Invalid input!';

        echo join(', ', explode(':', $first)) . "\r\n";
        echo join(', ', explode(':', $second)) . "\r\n";

        if (explode(':', $first) != 3 || explode(':', $second) != 3)
            return 'Invalid input!';

        return self::secondsToTime(self::timeToSeconds($first) + self::timeToSeconds($second));
    }
}
