<?php
require_once 'TimeCalculator.php';
require_once 'Calculator.php';

function entry(): void {
    echo 'TimeCalculator (TC) / Calculator (C): ';
    $selection = readline();

    if ($selection == 'TC') {
        echo 'Enter first time value (hh:mm:ss): ';
        $first = readline();
        echo 'Enter first time value (hh:mm:ss): ';
        $second = readline();

        $result = TimeCalculator::sumTime($first, $second);

        echo sprintf('Result: %s', $result);
    }
    elseif ($selection == 'C') {
        echo 'Calculation expression: ';
        $input = readline();

        $result = Calculator::calculate($input);

        echo sprintf('Result: %s', $result);
    }
}

entry();
