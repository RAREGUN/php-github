<?php

var_dump($argc);
var_dump($argv);

if ($argc < 2) {
    echo 'Insufficient arguments count!';
    return;
}

echo sprintf('Result: %s', Calculator::calculate($argv[1]));
readline();

class Calculator
{
    private static array $possibleCharacters = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', '+', '-', '*', '/'];
    private static array $operators = ['+', '-', '*', '/'];
    private static array $priorityOperators = ['*', '/'];

    public static function calculate(string $str): string
    {
        foreach (str_split($str) as $char)
            if (!in_array($char, self::$possibleCharacters))
                return 'Invalid input!';

        $explodedToArray = self::explode($str);

        $operatorsCount = 0;
        $operandsCount = 0;

        foreach ($explodedToArray as $item) {
            if ($item[0] === 1) $operandsCount++;
            elseif ($item[0] !== 0) $operatorsCount++;
        }

        if (count($explodedToArray) < 3 || $operandsCount - $operatorsCount !== 1)
            return 'Wrong expression!';

        if ($operandsCount > 5)
            return 'Operators count must be less than 5!';

        $calculatedResult = self::calculateFromArray($explodedToArray);

        return $calculatedResult;
    }

    private static function explode(string $str): array
    {
        $result = array();
        $tempValue = '';

        for ($idx = 0; $idx < strlen($str); $idx++) {
            $letter = $str[$idx];
            $isOperator = in_array($letter, self::$operators) && $idx !== 0;

            if ($isOperator) {
                $result[] = [1, $tempValue];
                $tempValue = '';

                $result[] = [in_array($letter, self::$priorityOperators) ? 3 : 2, $letter];
            } else $tempValue = $tempValue . $letter;
        }

        if ($tempValue !== '')
            $result[] = [1, $tempValue];

        return $result;
    }

    private static function calculateFromArray(array $arr): string
    {
        $end = false;
        $result = 0;

        while (!$end) {
            $lastOperatorIndex = -1;
            $lastOperatorPriority = 2;

            for ($idx = count($arr) - 1; $idx >= 0; $idx--) {
                if ($arr[$idx][0] >= $lastOperatorPriority) {
                    $lastOperatorIndex = $idx;
                    $lastOperatorPriority = $arr[$idx][0];
                }

                if ($idx === 0) {
                    if ($lastOperatorIndex < 0) {
                        $end = true;
                        break;
                    }

                    $leftOperandTempIndex = $lastOperatorIndex - 1;
                    for (; $leftOperandTempIndex >= 0; $leftOperandTempIndex--) {
                        if ($arr[$leftOperandTempIndex][0] > 0)
                            break;
                    }

                    $rightOperandTempIndex = $lastOperatorIndex + 1;
                    for (; $rightOperandTempIndex < count($arr); $rightOperandTempIndex++) {
                        if ($arr[$rightOperandTempIndex][0] > 0)
                            break;
                    }

                    $lastOperatorValue = $arr[$lastOperatorIndex][1];
                    $leftOperand = $arr[$leftOperandTempIndex][1];
                    $rightOperand = $arr[$rightOperandTempIndex][1];
                    $resultValue = 0;

                    if ($lastOperatorValue === '+') $resultValue = $leftOperand + $rightOperand;
                    elseif ($lastOperatorValue === '-') $resultValue = $leftOperand - $rightOperand;
                    elseif ($lastOperatorValue === '*') $resultValue = $leftOperand * $rightOperand;
                    elseif ($lastOperatorValue === '/') {
                        if ($rightOperand === '0')
                            return 'Error! Division by zero exception.';

                        $resultValue = $leftOperand / $rightOperand;
                    }

                    $arr[$lastOperatorIndex][0] = 1;
                    $arr[$lastOperatorIndex][1] = $resultValue;
                    $arr[$leftOperandTempIndex][0] = 0;
                    $arr[$leftOperandTempIndex][1] = 0;
                    $arr[$rightOperandTempIndex][0] = 0;
                    $arr[$rightOperandTempIndex][1] = 0;

                    $result = $resultValue;

                    break;
                }
            }
        }

        return $result;
    }
}
