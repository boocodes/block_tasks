<?php
declare(strict_types=1);


function sum(int $a, int $b): int {
    return $a + $b;
}





try{
    sum("1", "2");
}
catch (Throwable $e){
    echo "TypeError: " . $e->getMessage() . "\n";
}

try{
    throw new RuntimeException("something went wrong");
}
catch (Throwable $e){
    echo "RuntimeException: " . $e->getMessage() . "\n";
}

try{
    hello_world();
}
catch (Throwable $e){
    echo "Error: " . $e->getMessage() . "\n";
}