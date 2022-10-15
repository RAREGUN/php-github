<?php

class Parallelogram extends Shape
{
    private float $base = 0;
    private float $side = 0;
    private float $height = 0;

    public function __construct(float $base, float $side, float $height) {
        $this->base = $base;
        $this->side = $side;
        $this->height = $height;
    }

    protected function getPerimeter(): float {
        return ($this->base + $this->side) * 2;
    }

    protected function getArea(): float {
        return $this->base * $this->height;
    }

    public function __toString() {
        return sprintf("(Shape: Parallelogram / Perimeter: %s / Area: %s)", $this->getPerimeter(), $this->getArea());
    }
}