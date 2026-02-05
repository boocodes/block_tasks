<?php

$x = 10;

$a = function () use ($x)  {
    return $x;
};

echo $x . "\n";

$x = 20;

echo $a() . "\n";


echo "by reference" . "\n";

$x = 10;

$a = function () use (&$x) {
    return $x;
};

echo $x . "\n";

$x = 20;

echo $a() . "\n";