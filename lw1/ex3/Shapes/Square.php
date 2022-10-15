<?php

namespace Shapes;

class Square extends Shape
{
    private float $side = 0;

    public function __construct(float $side)
    {
        $this->side = $side;
    }

    protected function getPerimeter(): float
    {
        return $this->side * 4;
    }

    protected function getArea(): float
    {
        return $this->side * $this->side;
    }

    public function __toString()
    {
        return sprintf("(Shape: Square / Perimeter: %s / Area: %s)", $this->getPerimeter(), $this->getArea());
    }
}