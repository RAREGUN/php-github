<?php

class Date
{
    private int $day = 0;
    private int $month = 0;
    private int $year = 0;

    public static array $days = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    public static array $daysOfWeek = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    public function __construct(int $dd, int $mm, int $yy)
    {
        if ($mm >= 1 && $mm <= 12) {
            $this->month = $mm;
        } else {
            throw new Exception("Month must be 1-12!");
        }
        if ($yy >= 1900 && $yy <= 2100) {
            $this->year = $yy;
        } else {
            throw new Exception("Year must be >= 1900 and <= 2100!");
        }
        if (($this->month == 2 && Date::isLeapYear($this->year) && $dd >= 1 && $dd <= 29)
            || ($dd >= 1 && $dd <= Date::$days[$this->month])) {
            $this->day = $dd;
        } else {
            throw new Exception("Day is out of range for current month and year!");
        }
    }

    public function getDuplicate(): Date
    {
        return new Date($this->day, $this->month, $this->year);
    }

    public function decrement()
    {
        if ($this->day == 1) {
            if ($this->month == 1) {
                $this->year--;
                $this->month = 12;
            } else $this->month--;

            /** @noinspection PhpSuspiciousNameCombinationInspection */
            $this->day = $this->getEndOfMonth();
        } else $this->day--;
    }

    public function getEndOfMonth(): int
    {
        if ($this->month == 2 && Date::isLeapYear($this->year))
            return 29;

        return Date::$days[$this->month];
    }

    public static function isLeapYear(int $yy): bool
    {
        return $yy % 400 == 0 || $yy % 100 != 0 && $yy % 4 == 0;
    }

    public function isEquals(Date $comparable): bool
    {
        $yearDiff = $this->year - $comparable->year;
        $monthDiff = $this->month - $comparable->month;
        $dayDiff = $this->day - $comparable->day;

        return $yearDiff == 0 && $monthDiff == 0 && $dayDiff == 0;
    }

    public function isGreater(Date $comparable): bool
    {
        $yearDiff = $this->year - $comparable->year;
        $monthDiff = $this->month - $comparable->month;
        $dayDiff = $this->day - $comparable->day;

        if ($yearDiff == 0 && $monthDiff == 0 && $dayDiff == 0) // Exclude unexpected behaviour :)
            return false;

        if ($yearDiff > 0)
            return true;
        elseif ($yearDiff == 0) {
            if ($monthDiff > 0)
                return true;
            elseif ($monthDiff == 0)
                return $dayDiff > 0;
        }

        return false;
    }

    public function diffDay(Date $comparable): int
    {
        if ($this->isEquals($comparable))
            return 0;

        $greaterDate = $comparable;
        $lessDate = $this;
        $modifier = 1;

        if ($this->isGreater($comparable)) {
            $greaterDate = $this;
            $lessDate = $comparable;
            $modifier = -1;
        }

        $greaterDate = $greaterDate->getDuplicate();

        $diff = 0;
        while (!$lessDate->isEquals($greaterDate)) {
            $greaterDate->decrement();
            $diff++;
        }

        return $diff * $modifier;
    }

    public function minusDay($value): Date
    {
        $duplicate = $this->getDuplicate();

        for ($idx = 0; $idx < $value; $idx++) {
            $duplicate->decrement();
        }

        return $duplicate;
    }

    public function getDateOfWeek(): string
    {
        $a = (14 - $this->month) / 12;
        $y = $this->year - $a;
        $res = ($this->day + $y + $y / 4 - $y / 100 + $y / 400 + 31 * ($this->month + 12 * $a - 2) / 12) % 7;

        return Date::$daysOfWeek[$res];
    }

    public function format($lang): string
    {
        if ($lang == 'en')
            return sprintf("%s-%s-%s",
                str_pad($this->year, 2, '0', STR_PAD_LEFT),
                str_pad($this->month, 2, '0', STR_PAD_LEFT),
                str_pad($this->day, 2, '0', STR_PAD_LEFT));

        return $this;
    }

    public function __toString()
    {
        return sprintf("%s.%s.%s",
            str_pad($this->day, 2, '0', STR_PAD_LEFT),
            str_pad($this->month, 2, '0', STR_PAD_LEFT),
            str_pad($this->year, 2, '0', STR_PAD_LEFT));
    }
}

$date = new Date(1, 2, 2001);
$date2 = new Date(1, 4, 2001);
printf("%s\r\n", $date->diffDay($date2));    // 59
printf("%s\r\n", $date->minusDay(4));  // ’28.01.2001’
printf("%s\r\n", $date->getDateOfWeek());    // ‘Thursday’
printf("%s\r\n", $date->format('ru'));  // ’01.02.2001’
printf("%s\r\n", $date->format('en'));  // ‘2001-02-01’
readline();
