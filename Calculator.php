<?php

class Calculator
{
    static array $possibleCharacters = [ '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', '+', '-', '*', '/' ];
    static array $operands = ['+', '-', '*', '/'];
    static array $priorityOperands = ['*', '/'];

    static function calculate($str): string {
        foreach (str_split($str) as $char)
            if (!in_array($char, self::$possibleCharacters))
                return 'Invalid input!';

        $explodedToArray = self::explode($str);

        if (count($explodedToArray) < 3)
            return 'Wrong expression!';

        $calculatedResult = self::calculateFromArray($explodedToArray);

        return $calculatedResult;
    }

    static function explode($str): array {
        $result = array();
        $tempValue = '';

        for ($idx = 0; $idx < strlen($str); $idx++) {
            $letter = $str[$idx];
            $isOperator = in_array($letter, self::$operands) && $idx != 0;

            if ($isOperator) {
                $result[] = [1, $tempValue];
                $tempValue = '';

                $result[] = [in_array($letter, self::$priorityOperands) ? 3 : 2, $letter];
            }
            else $tempValue = $tempValue . $letter;

            if ($idx == strlen($str) - 1)
                $result[] = [1, $tempValue];
        }

        return $result;
    }

    static function calculateFromArray($arr):string {
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

                if ($idx == 0) {
                    if ($lastOperatorIndex < 0) {
                        $end = true;
                        break;
                    }

                    $leftOperandTempIndex = $lastOperatorIndex - 1;
                    for (;$leftOperandTempIndex >= 0; $leftOperandTempIndex--) {
                        if ($arr[$leftOperandTempIndex][0] > 0)
                            break;
                    }

                    $rightOperandTempIndex = $lastOperatorIndex + 1;
                    for (;$rightOperandTempIndex < count($arr); $rightOperandTempIndex++) {
                        if ($arr[$rightOperandTempIndex][0] > 0)
                            break;
                    }

                    $lastOperatorValue = $arr[$lastOperatorIndex][1];
                    $leftOperand = $arr[$leftOperandTempIndex][1];
                    $rightOperand = $arr[$rightOperandTempIndex][1];
                    $resultValue = 0;

                    if ($lastOperatorValue == '+') $resultValue = $leftOperand + $rightOperand;
                    elseif ($lastOperatorValue == '-') $resultValue = $leftOperand - $rightOperand;
                    elseif ($lastOperatorValue == '*') $resultValue = $leftOperand * $rightOperand;
                    elseif ($lastOperatorValue == '/') {
                        if ($rightOperand == '0')
                            return 'Error! Division by zero exception.';

                        $resultValue = $leftOperand / $rightOperand;
                    };

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
