<?php

namespace Shapes;

abstract class Shape
{
    protected abstract function getPerimeter(): float;

    protected abstract function getArea(): float;
}