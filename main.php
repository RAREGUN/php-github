<?php
require_once 'TimeCalculator.php';
require_once 'Calculator.php';

function entry(): void {
    $selection = readline('TimeCalculator (TC) / Calculator (C)');

    if ($selection == 'TC') {
        $first = readline('Enter first time value (hh:mm:ss): ');
        $second = readline('Enter first time value (hh:mm:ss): ');

        $result = TimeCalculator::sumTime($first, $second);

        printf('Result: %s', $result);
    }
    elseif ($selection == 'C') {
        $input = readline('Calculation expression: ');

        $result = Calculator::calculate($input);

        printf('Result: %s', $result);
    }
}

entry();
