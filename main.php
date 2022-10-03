<?php
require_once "TimeCalculator.php";

function entry(): void
{
    $first = readline("Enter first time value (hh:mm:ss): ");
    $second = readline("Enter first time value (hh:mm:ss): ");

    echo "Result: " . TimeCalculator::sumTime($first, $second);
}

entry();
