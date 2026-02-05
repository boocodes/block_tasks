<?php


function arraySource(int $n): array
{
    $result = [];
    for($i = 0; $i < $n; $i++)
    {
        $result[] = $i;
    }
    return $result;
}

function generatorSource(int $n): Generator
{
    for($i = 0; $i < $n; $i++)
    {
        yield $i;
    }
}

echo "Array: " . "\n";

$startTime = microtime(true);
$startMemory = memory_get_usage();

$plainBuiltArray = arraySource(100000);
echo "size: " . count($plainBuiltArray) . "\n";
echo "memory: " . (memory_get_usage() - $startMemory) / 1024 . "\n";
echo "time: " . (microtime(true) - $startTime) . "\n";

echo "Generator: " . "\n";
$startTime = microtime(true);
$startMemory = memory_get_usage();

$generatedArray = generatorSource(100000);

echo "size: " . iterator_count($generatedArray) . "\n";
echo "memory: " . (memory_get_usage() - $startMemory) / 1024 . "\n";
echo "time: " . (microtime(true) - $startTime) . "\n";