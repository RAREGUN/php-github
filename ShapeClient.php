<?php
require_once('Shapes/Shape.php');
require_once('Shapes/Square.php');
require_once('Shapes/Rectangle.php');
require_once('Shapes/Parallelogram.php');

$square = new Square(8);
printf("%s\r\n", $square);

$rectangle = new Rectangle(8, 6);
printf("%s\r\n", $rectangle);

$parallelogram = new Parallelogram(12, 6, 5);
printf("%s\r\n", $parallelogram);

readline();