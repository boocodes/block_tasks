<?php

$test_array = array_fill(0, 100000, "test");

function passByValue(array $a): array
{

    echo "Success\n";
    return $a;
}

function passByValueAndModify(array $a): array
{
    $a[10] = "hello_world";
    echo "Success\n";
    return $a;
}

function passByReference(array &$a): array
{
    $a[10] = "hello_world";
    echo "Success\n";
    return $a;
}

// 1. Pass by value
echo "pass by value: \n";
$memory_before = memory_get_peak_usage(false);
$startTime = microtime(true);
passByValue($test_array);
$endTime = microtime(true);
$memory_after = memory_get_peak_usage(false);
echo "Time: " . round(($endTime - $startTime) * 1000, 4) . " ms\n";
echo "Memory: " . round(($memory_after - $memory_before) / 1024) . " KB\n";
echo "\n";

// 2. Pass by value and modify
echo "pass by value and modify: \n";
$memory_before = memory_get_peak_usage(false);
$startTime = microtime(true);
passByValueAndModify($test_array);
$endTime = microtime(true);
$memory_after = memory_get_peak_usage(false);
echo "Time: " . round(($endTime - $startTime) * 1000, 4) . " ms\n";
echo "Memory: " . round(($memory_after - $memory_before) / 1024) . " KB\n";
echo "\n";

// 3. Pass by reference and modify
echo "pass by reference and modify: \n";
$memory_before = memory_get_peak_usage(false);
$startTime = microtime(true);
passByReference($test_array);
$endTime = microtime(true);
$memory_after = memory_get_peak_usage(false);
echo "Time: " . round(($endTime - $startTime) * 1000, 4) . " ms\n";
echo "Memory: " . round(($memory_after - $memory_before) / 1024) . " KB\n";
echo "\n";