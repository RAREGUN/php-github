<?php

class Rectangle extends Shape
{
    private float $side_a = 0;
    private float $side_b = 0;

    public function __construct(float $side_a, float $side_b) {
        $this->side_a = $side_a;
        $this->side_b = $side_b;
    }

    protected function getPerimeter(): float
    {
        return ($this->side_a + $this->side_b) * 2;
    }

    protected function getArea(): float
    {
        return $this->side_a * $this->side_b;
    }

    public function __toString() {
        return sprintf("(Shape: Rectangle / Perimeter: %s / Area: %s)", $this->getPerimeter(), $this->getArea());
    }
}