<?php
// run_loose.php - без строгой проверки типов
declare(strict_types=1);


require_once 'sum.php';

echo "sum(\"1\", \"2\"): ";
try {
    $result = sum("1", "2");
    echo "$result\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
}

echo "sum(1.2, 2.8): ";
try {
    $result = sum(1.2, 2.8);
    echo "$result\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
}

echo "sum(null, 1): ";
try {
    $result = sum(null, 1);
    echo "$result\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
