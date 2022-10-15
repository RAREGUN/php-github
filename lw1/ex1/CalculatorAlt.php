<?php

$calculator = new CalculatorAlt();

echo sprintf("Result #0: %s\r\n", $calculator->sum(1)->sum(2)->product(3)->division(3)->getResult());
echo sprintf("Result #1: %s\r\n", $calculator->sum(3)->sum(3)->minus(3)->division(3)->getResult());
echo sprintf("Result #2: %s\r\n", $calculator->sum(1.4)->sum(2.6)->product(4)->getResult());
echo sprintf("Result #3: %s\r\n", $calculator->sum(1)->sum(2)->product(3)->division(0)->getResult());
readline();

class CalculatorAlt
{
    private float $value = 0;

    public function sum($arg): self
    {
        $this->value += $arg;

        return $this;
    }

    public function minus($arg): self
    {
        $this->value -= $arg;

        return $this;
    }

    public function product($arg): self
    {
        $this->value *= $arg;

        return $this;
    }

    public function division($arg): self
    {
        if ($arg == 0)
            throw new Exception('Division by zero.');

        $this->value /= $arg;

        return $this;
    }

    public function getResult(): float
    {
        $tempValue = $this->value;
        $this->value = 0;

        return $tempValue;
    }
}
